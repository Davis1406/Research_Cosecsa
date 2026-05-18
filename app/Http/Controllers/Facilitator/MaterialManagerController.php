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
            ->orderBy('category')->orderBy('title')->get();
        return view('facilitator.material-manager.index', compact('materials'));
    }

    public function create()
    {
        $speakers = Speaker::orderBy('name')->get();
        return view('facilitator.material-manager.form', [
            'material' => null,
            'speakers' => $speakers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'type'        => 'required|in:document,presentation,video',
            'category'    => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'speaker_id'  => 'nullable|exists:speakers,id',
            'file'        => 'nullable|file|max:51200', // 50MB
        ]);

        $externalUrl = null;

        if ($request->hasFile('file')) {
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
        $speakers = Speaker::orderBy('name')->get();
        return view('facilitator.material-manager.form', compact('material', 'speakers'));
    }

    public function update(Request $request, TrainingMaterial $material)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'type'        => 'required|in:document,presentation,video',
            'category'    => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'speaker_id'  => 'nullable|exists:speakers,id',
            'file'        => 'nullable|file|max:51200',
        ]);

        $externalUrl = $material->external_url;

        if ($request->hasFile('file')) {
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

    private function subDirForType(string $type): string
    {
        return match($type) {
            'video'        => 'videos',
            'document'     => 'documents',
            'presentation' => 'presentations',
            default        => 'documents',
        };
    }
}
