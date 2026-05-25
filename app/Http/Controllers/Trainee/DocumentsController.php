<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\TraineeDocument;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function index()
    {
        $trainee   = auth()->user()->trainee;
        $documents = $trainee
            ? $trainee->documents()->with('comments.user')->latest()->get()
            : collect();
        return view('trainee.documents', compact('documents', 'trainee'));
    }

    public function storeMedia(Request $request)
    {
        $request->validate(['file' => 'required|file|max:20480']);

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

    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:Presentation,CV,Certificate,ID,Other',
            'title'         => 'nullable|string|max:255',
            // Accept either a Dropzone temp token (string) or a direct file upload
            'file'          => $request->hasFile('file')
                                ? 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,ppt,pptx|max:20480'
                                : 'required|string',
        ]);

        $trainee = auth()->user()->trainee;
        if (!$trainee) {
            return back()->with('error', 'No trainee profile linked to your account.');
        }

        if ($request->hasFile('file')) {
            // Direct upload fallback
            $file     = $request->file('file');
            $origName = $file->getClientOriginalName();
            $path     = $file->store('trainee-documents/' . $trainee->id, 'public');
        } else {
            // Dropzone temp upload: move from tmp/uploads to public storage
            $tmpName  = $request->input('file');
            $tmpPath  = storage_path('tmp/uploads/' . $tmpName);
            if (!file_exists($tmpPath)) {
                return back()->withInput()->with('error', 'Uploaded file not found. Please try again.');
            }
            $origName = preg_replace('/^\w+_/', '', $tmpName, 1); // strip the uniqid_ prefix
            $dest     = 'trainee-documents/' . $trainee->id . '/' . $tmpName;
            \Storage::disk('public')->put($dest, file_get_contents($tmpPath));
            @unlink($tmpPath);
            $path = $dest;
        }

        TraineeDocument::create([
            'trainee_id'    => $trainee->id,
            'document_type' => $request->document_type,
            'title'         => $request->title ?: $origName,
            'original_name' => $origName,
            'file_path'     => $path,
        ]);

        return back()->with('message', 'Document uploaded successfully.');
    }

    public function destroy(TraineeDocument $document)
    {
        if ($document->trainee->user_id !== auth()->id()) {
            abort(403);
        }
        \Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return back()->with('message', 'Document removed.');
    }
}
