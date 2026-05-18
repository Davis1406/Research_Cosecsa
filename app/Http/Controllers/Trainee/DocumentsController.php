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

    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:Presentation,CV,Certificate,ID,Other',
            'title'         => 'nullable|string|max:255',
            'file'          => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,ppt,pptx|max:20480',
        ]);

        $trainee = auth()->user()->trainee;
        if (!$trainee) {
            return back()->with('error', 'No trainee profile linked to your account.');
        }

        $file = $request->file('file');
        $path = $file->store('trainee-documents/' . $trainee->id, 'public');

        TraineeDocument::create([
            'trainee_id'    => $trainee->id,
            'document_type' => $request->document_type,
            'title'         => $request->title ?: $file->getClientOriginalName(),
            'original_name' => $file->getClientOriginalName(),
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
