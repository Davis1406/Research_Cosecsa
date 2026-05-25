<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\TrainingMaterial;
use App\Speaker;
use Illuminate\Http\Request;

class MaterialManagerController extends Controller
{
    public function index()
    {
        $materials = TrainingMaterial::with('facilitator')
            ->latest()->get();
        return view('facilitator.material-manager.index', compact('materials'));
    }

    public function create()
    {
        $speakers   = Speaker::orderBy('name')->get();
        $categories = TrainingMaterial::distinct()->orderBy('category')->pluck('category')->filter()->values();
        return view('facilitator.material-manager.form', [
            'material'   => null,
            'speakers'   => $speakers,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:document,presentation,video,youtube,audio',
            'category'     => 'nullable|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'speaker_id'   => 'nullable|exists:speakers,id',
            'file'         => 'nullable|file|max:102400|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,gif,mp4,mp3,zip,txt,csv', // 100MB
            'youtube_url'  => 'nullable|url|max:500',
        ]);

        $externalUrl = null;

        if ($validated['type'] === 'youtube') {
            $externalUrl = $request->input('youtube_url');
        } elseif ($request->hasFile('file')) {
            // Direct file upload fallback
            $file     = $request->file('file');
            $origName = $file->getClientOriginalName();
            $subDir   = $this->subDirForType($validated['type']);
            $destDir  = public_path('materials/' . $subDir);
            if (!is_dir($destDir)) { mkdir($destDir, 0755, true); }
            $file->move($destDir, $origName);
            $externalUrl = '/research/materials/' . $subDir . '/' . $origName;
        } elseif ($request->input('file') && !$request->hasFile('file')) {
            // Dropzone temp-upload token
            $tmpName  = $request->input('file');
            $tmpPath  = storage_path('tmp/uploads/' . $tmpName);
            if (file_exists($tmpPath)) {
                $ext      = strtolower(pathinfo($tmpName, PATHINFO_EXTENSION));
                $detType  = $this->typeFromExt($ext) ?? $validated['type'];
                $subDir   = $this->subDirForType($detType);
                $destDir  = public_path('materials/' . $subDir);
                if (!is_dir($destDir)) { mkdir($destDir, 0755, true); }
                rename($tmpPath, $destDir . '/' . $tmpName);
                $externalUrl = '/research/materials/' . $subDir . '/' . $tmpName;
                $validated['type'] = $detType;
            }
        }

        TrainingMaterial::create([
            'title'        => $validated['title'],
            'type'         => $validated['type'],
            'category'     => $validated['category'] ?? null,
            'description'  => $validated['description'] ?? null,
            'speaker_id'   => $validated['speaker_id'] ?? null,
            'external_url' => $externalUrl,
        ]);

        return redirect()->route('facilitator.material-manager.index')
                         ->with('message', 'Material added successfully.');
    }

    public function edit(TrainingMaterial $material)
    {
        $speakers   = Speaker::orderBy('name')->get();
        $categories = TrainingMaterial::distinct()->orderBy('category')->pluck('category')->filter()->values();
        return view('facilitator.material-manager.form', compact('material', 'speakers', 'categories'));
    }

    public function update(Request $request, TrainingMaterial $material)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:document,presentation,video,youtube,audio',
            'category'     => 'nullable|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'speaker_id'   => 'nullable|exists:speakers,id',
            'file'         => 'nullable|file|max:102400',
            'youtube_url'  => 'nullable|url|max:500',
        ]);

        $externalUrl = $material->external_url;

        if ($validated['type'] === 'youtube') {
            $externalUrl = $request->input('youtube_url') ?: $externalUrl;
        } elseif ($request->hasFile('file')) {
            // Direct file upload fallback
            $file     = $request->file('file');
            $origName = $file->getClientOriginalName();
            $subDir   = $this->subDirForType($validated['type']);
            $destDir  = public_path('materials/' . $subDir);
            if (!is_dir($destDir)) { mkdir($destDir, 0755, true); }
            $file->move($destDir, $origName);
            $externalUrl = '/research/materials/' . $subDir . '/' . $origName;
        } elseif ($request->input('file') && !$request->hasFile('file')) {
            // Dropzone temp-upload token
            $tmpName  = $request->input('file');
            $tmpPath  = storage_path('tmp/uploads/' . $tmpName);
            if (file_exists($tmpPath)) {
                $ext     = strtolower(pathinfo($tmpName, PATHINFO_EXTENSION));
                $detType = $this->typeFromExt($ext) ?? $validated['type'];
                $subDir  = $this->subDirForType($detType);
                $destDir = public_path('materials/' . $subDir);
                if (!is_dir($destDir)) { mkdir($destDir, 0755, true); }
                rename($tmpPath, $destDir . '/' . $tmpName);
                $externalUrl = '/research/materials/' . $subDir . '/' . $tmpName;
                $validated['type'] = $detType;
            }
        }

        $material->update([
            'title'        => $validated['title'],
            'type'         => $validated['type'],
            'category'     => $validated['category'] ?? null,
            'description'  => $validated['description'] ?? null,
            'speaker_id'   => $validated['speaker_id'] ?? null,
            'external_url' => $externalUrl,
        ]);

        return redirect()->route('facilitator.material-manager.index')
                         ->with('message', 'Material updated successfully.');
    }

    public function destroy(TrainingMaterial $material)
    {
        $material->delete();
        return redirect()->route('facilitator.material-manager.index')
                         ->with('message', 'Material deleted.');
    }

    /**
     * Accept a temp-file upload via Dropzone (mirrors admin storeMedia).
     */
    public function storeMedia(Request $request)
    {
        $request->validate(['file' => 'required|file|max:102400']);

        $dangerousExts = ['php','php3','php4','php5','phtml','exe','sh','bat','cmd','js','vbs'];
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

    /**
     * Replace only the file of a material (called from the inline replace panel).
     */
    public function replaceFile(Request $request, TrainingMaterial $material)
    {
        $tmpName = $request->input('file');
        if (!$tmpName) {
            return back()->with('error', 'No file received.');
        }

        $tmpPath = storage_path('tmp/uploads/' . $tmpName);

        if (!file_exists($tmpPath)) {
            return back()->with('error', 'Uploaded file not found. Please try again.');
        }

        // Auto-detect material type from the new file's extension
        $ext          = strtolower(pathinfo($tmpName, PATHINFO_EXTENSION));
        $detectedType = $this->typeFromExt($ext);
        $updates      = [];
        if ($detectedType && $detectedType !== $material->type) {
            $updates['type'] = $detectedType;
        }

        // If the material uses Spatie MediaLibrary, replace there
        if ($material->file) {
            $material->clearMediaCollection('file');
            $material->addMedia($tmpPath)->toMediaCollection('file');
            $updates['external_url'] = null; // ensure media file is used
        } else {
            // Store in public/materials and update external_url
            $origName = preg_replace('/^\w+_/', '', $tmpName);
            $subDir   = $this->subDirForType($detectedType ?? $material->type);
            $destDir  = public_path('materials/' . $subDir);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            rename($tmpPath, $destDir . '/' . $origName);
            $updates['external_url'] = '/materials/' . $subDir . '/' . $origName;
        }

        if ($updates) {
            $material->update($updates);
        }

        return redirect()->route('facilitator.material-manager.index')
                         ->with('message', 'File for "' . $material->title . '" replaced successfully.');
    }

    /** Map a file extension to a material type slug. */
    private function typeFromExt(string $ext): ?string
    {
        return match(true) {
            in_array($ext, ['ppt','pptx'])                        => 'presentation',
            in_array($ext, ['pdf','doc','docx','xls','xlsx','txt','csv','zip']) => 'document',
            in_array($ext, ['mp4','mov','webm','avi'])            => 'video',
            in_array($ext, ['mp3','wav','ogg','m4a'])             => 'audio',
            in_array($ext, ['jpg','jpeg','png','gif','webp'])     => 'image',
            default                                               => null,
        };
    }

    private function subDirForType(string $type): string
    {
        return match($type) {
            'video'        => 'videos',
            'audio'        => 'audio',
            'document'     => 'documents',
            'presentation' => 'presentations',
            default        => 'documents',
        };
    }
}
