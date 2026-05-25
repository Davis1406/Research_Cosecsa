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
            $file     = $request->file('file');
            $origName = $file->getClientOriginalName();
            $subDir   = $this->subDirForType($validated['type']);
            $destDir  = public_path('materials/' . $subDir);

            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }

            $file->move($destDir, $origName);
            $externalUrl = '/research/materials/' . $subDir . '/' . $origName;
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
            $file     = $request->file('file');
            $origName = $file->getClientOriginalName();
            $subDir   = $this->subDirForType($validated['type']);
            $destDir  = public_path('materials/' . $subDir);

            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }

            $file->move($destDir, $origName);
            $externalUrl = '/research/materials/' . $subDir . '/' . $origName;
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

        // If the material uses Spatie MediaLibrary, replace there
        if ($material->file) {
            $material->clearMediaCollection('file');
            $material->addMedia($tmpPath)->toMediaCollection('file');
            // Clear any external_url so the media file is used
            $material->update(['external_url' => null]);
        } else {
            // Otherwise store in public/materials and update external_url
            $origName = preg_replace('/^\w+_/', '', $tmpName); // strip the uniqid_ prefix
            $subDir   = $this->subDirForType($material->type);
            $destDir  = public_path('materials/' . $subDir);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            rename($tmpPath, $destDir . '/' . $origName);
            $material->update(['external_url' => '/materials/' . $subDir . '/' . $origName]);
        }

        return redirect()->route('facilitator.material-manager.index')
                         ->with('message', 'File for "' . $material->title . '" replaced successfully.');
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
