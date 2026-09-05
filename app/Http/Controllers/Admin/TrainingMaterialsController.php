<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTrainingMaterialRequest;
use App\Http\Requests\StoreTrainingMaterialRequest;
use App\Http\Requests\UpdateTrainingMaterialRequest;
use App\Speaker;
use App\TrainingMaterial;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrainingMaterialsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('training_material_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courseType = $this->resolveCourseType($request);

        $trainingMaterials = TrainingMaterial::with('facilitator')->course($courseType)->orderBy('id', 'desc')->get();

        return view('admin.training-materials.index', compact('trainingMaterials', 'courseType'));
    }

    /**
     * Resolve and validate the ?course= query param, falling back to the configured default.
     */
    private function resolveCourseType(Request $request)
    {
        $courseType = $request->query('course', config('courses.default'));

        return array_key_exists($courseType, config('courses.types')) ? $courseType : config('courses.default');
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('training_material_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courseType   = $this->resolveCourseType($request);
        $facilitators = Speaker::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.training-materials.create', compact('facilitators', 'courseType'));
    }

    public function store(StoreTrainingMaterialRequest $request)
    {
        $trainingMaterial = TrainingMaterial::create($request->all());

        if ($request->input('file', false)) {
            try {
                $trainingMaterial->addMedia(storage_path('tmp/uploads/' . $request->input('file')))
                    ->toMediaCollection('file');
            } catch (\Exception $e) {
                $trainingMaterial->delete();
                return back()->withInput()->with('error', 'File could not be saved: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.training-materials.index', ['course' => $trainingMaterial->course_type])
            ->with('message', 'Material uploaded successfully.');
    }

    public function edit(TrainingMaterial $trainingMaterial)
    {
        abort_if(Gate::denies('training_material_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courseType   = $trainingMaterial->course_type;
        $facilitators = Speaker::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $trainingMaterial->load('facilitator');

        return view('admin.training-materials.edit', compact('facilitators', 'trainingMaterial', 'courseType'));
    }

    public function update(UpdateTrainingMaterialRequest $request, TrainingMaterial $trainingMaterial)
    {
        $trainingMaterial->update($request->all());

        if ($request->input('file', false)) {
            // A new file was uploaded — replace the existing one
            $tmpName = $request->input('file');
            $tmpPath = storage_path('tmp/uploads/' . $tmpName);

            if (file_exists($tmpPath) &&
                (!$trainingMaterial->file || $tmpName !== $trainingMaterial->file->file_name)) {
                $trainingMaterial->clearMediaCollection('file');
                $trainingMaterial->addMedia($tmpPath)->toMediaCollection('file');

                // Auto-update type and clear external_url so the new media file is used
                $ext          = strtolower(pathinfo($tmpName, PATHINFO_EXTENSION));
                $detectedType = $this->typeFromExt($ext);
                $trainingMaterial->update([
                    'external_url' => null,
                    'type'         => $detectedType ?? $trainingMaterial->type,
                ]);
            }
        } elseif ($request->input('remove_file') === '1' && $trainingMaterial->file) {
            // Explicit "remove file" checkbox — only then delete
            $trainingMaterial->clearMediaCollection('file');
        }
        // Otherwise: no new file and no remove flag → keep existing file untouched

        // If the save was triggered from the viewer page, go back there
        if ($request->input('_from') === 'viewer') {
            return redirect()
                ->route('admin.training-materials.viewer', $trainingMaterial->id)
                ->with('message', 'File replaced successfully.');
        }

        return redirect()->route('admin.training-materials.index', ['course' => $trainingMaterial->course_type])
            ->with('message', 'Material updated successfully.');
    }

    /** Map a file extension to a material type slug. */
    private function typeFromExt(string $ext): ?string
    {
        return match(true) {
            in_array($ext, ['ppt','pptx'])                        => 'presentation',
            in_array($ext, ['xls','xlsx','csv'])                  => 'spreadsheet',
            in_array($ext, ['pdf','doc','docx','txt','zip'])      => 'document',
            in_array($ext, ['mp4','mov','webm','avi'])            => 'video',
            in_array($ext, ['mp3','wav','ogg','m4a'])             => 'audio',
            in_array($ext, ['jpg','jpeg','png','gif','webp'])     => 'image',
            in_array($ext, ['dta','do'])                          => 'stata',
            default                                               => null,
        };
    }

    public function show(TrainingMaterial $trainingMaterial)
    {
        abort_if(Gate::denies('training_material_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $trainingMaterial->load('facilitator');

        return view('admin.training-materials.show', compact('trainingMaterial'));
    }

    public function renderSlides(TrainingMaterial $trainingMaterial)
    {
        abort_if(Gate::denies('training_material_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($trainingMaterial->type !== 'presentation') {
            return response()->json(['error' => 'Not a presentation'], 400);
        }

        $filePath = null;
        if ($trainingMaterial->external_url) {
            // Convert URL path to filesystem path
            $urlPath  = $trainingMaterial->external_url;
            $relative = ltrim(str_replace('/cosecsa-training/', '', $urlPath), '/');
            $filePath = public_path($relative);
        }

        if (!$filePath || !file_exists($filePath)) {
            return response()->json(['error' => 'File not found: ' . $filePath], 404);
        }

        $script = base_path('scripts/pptx_to_html.py');
        $escaped = escapeshellarg($filePath);

        // Resolve python3 path — Apache's daemon user has a minimal PATH
        $python3 = trim(shell_exec('which python3 2>/dev/null') ?? '');
        if (!$python3 || !file_exists($python3)) {
            // Common locations on macOS / XAMPP
            foreach ([
                '/usr/bin/python3',
                '/usr/local/bin/python3',
                '/Library/Developer/CommandLineTools/usr/bin/python3',
                '/opt/homebrew/bin/python3',
                '/Applications/XAMPP/xamppfiles/bin/python3',
            ] as $candidate) {
                if (file_exists($candidate)) { $python3 = $candidate; break; }
            }
        }
        if (!$python3) {
            return response()->json(['error' => 'python3 not found on this server'], 500);
        }

        $escapedPython = escapeshellarg($python3);
        $output  = shell_exec("{$escapedPython} {$script} {$escaped} 2>&1");

        if (!$output) {
            return response()->json(['error' => 'Renderer returned no output (python: ' . $python3 . ')'], 500);
        }

        $data = json_decode($output, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON from renderer: ' . substr($output, 0, 300)], 500);
        }
        if (isset($data['error'])) {
            $msg = $data['error'];
            // Strip internal path from error for cleaner display
            if (str_contains($msg, 'Package not found') || str_contains($msg, 'Cannot open file')) {
                $msg = 'This presentation file appears to be corrupted or incomplete. Please re-upload the file.';
            }
            return response()->json(['error' => $msg], 500);
        }

        return response()->json($data);
    }

    public function viewer(TrainingMaterial $trainingMaterial)
    {
        abort_if(Gate::denies('training_material_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $trainingMaterial->load(['facilitator', 'schedules.speaker']);

        // Build the file URL to serve.
        // Spatie media file takes priority — external_url is only a fallback
        // for materials that were uploaded before the media library was used.
        $fileUrl = null;
        if ($trainingMaterial->file) {
            $fileUrl = $trainingMaterial->file->url;
        } elseif ($trainingMaterial->external_url) {
            $fileUrl = preg_replace('#^/research/#', '/', $trainingMaterial->external_url);
        }

        return view('admin.training-materials.viewer', compact('trainingMaterial', 'fileUrl'));
    }

    public function destroy(TrainingMaterial $trainingMaterial)
    {
        abort_if(Gate::denies('training_material_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $trainingMaterial->delete();

        return back()->with('message', 'Material deleted successfully.');
    }

    public function massDestroy(MassDestroyTrainingMaterialRequest $request)
    {
        TrainingMaterial::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeMedia(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400',
        ]);

        // Block only genuinely dangerous extensions
        $dangerousExts = ['php', 'php3', 'php4', 'php5', 'phtml', 'exe', 'sh', 'bat', 'cmd', 'js', 'vbs', 'py'];
        $ext = strtolower($request->file('file')->getClientOriginalExtension());
        if (in_array($ext, $dangerousExts)) {
            return response()->json(['error' => 'File type not allowed.'], 422);
        }

        $path = storage_path('tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $file = $request->file('file');
        $name = uniqid() . '_' . trim($file->getClientOriginalName());
        $file->move($path, $name);

        return response()->json(['name' => $name, 'original_name' => $file->getClientOriginalName()]);
    }
}
