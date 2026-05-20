<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Certificate;
use App\Trainee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with(['trainee', 'issuedBy'])
            ->latest()
            ->get();

        return view('facilitator.certificates.index', compact('certificates'));
    }

    public function create()
    {
        $trainees = Trainee::orderBy('name')->get();
        return view('facilitator.certificates.create', compact('trainees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trainee_ids'  => 'required|array|min:1',
            'trainee_ids.*'=> 'exists:trainees,id',
            'event_name'   => 'required|string|max:255',
            'venue'        => 'nullable|string|max:255',
            'event_date'   => 'required|string|max:100',
            'course_name'  => 'nullable|string|max:255',
            'sig1_name'    => 'nullable|string|max:255',
            'sig1_title'   => 'nullable|string|max:255',
            'sig1_image'   => 'nullable|image|max:2048',
            'sig2_name'    => 'nullable|string|max:255',
            'sig2_title'   => 'nullable|string|max:255',
            'sig2_image'   => 'nullable|image|max:2048',
        ]);

        // Handle signature uploads
        $sig1Path = null;
        $sig2Path = null;

        if ($request->hasFile('sig1_image')) {
            $sig1Path = $request->file('sig1_image')->store('certificates/signatures', 'public');
        }
        if ($request->hasFile('sig2_image')) {
            $sig2Path = $request->file('sig2_image')->store('certificates/signatures', 'public');
        }

        foreach ($request->trainee_ids as $traineeId) {
            Certificate::create([
                'trainee_id'   => $traineeId,
                'event_name'   => $request->event_name,
                'venue'        => $request->venue,
                'event_date'   => $request->event_date,
                'issued_by'    => auth()->id(),
                'course_name'  => $request->course_name ?: 'Fundamentals of Surgical Research Course',
                'sig1_name'    => $request->sig1_name,
                'sig1_title'   => $request->sig1_title,
                'sig1_path'    => $sig1Path,
                'sig2_name'    => $request->sig2_name,
                'sig2_title'   => $request->sig2_title,
                'sig2_path'    => $sig2Path,
                'generated_at' => now(),
            ]);
        }

        $count = count($request->trainee_ids);
        return redirect()->route('facilitator.certificates.index')->with('message', "{$count} certificate(s) generated successfully.");
    }

    public function preview(Certificate $certificate)
    {
        $certificate->load(['trainee', 'issuedBy']);
        return view('facilitator.certificates.preview', compact('certificate'));
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
        return redirect()->route('facilitator.certificates.index')->with('message', 'Certificate deleted.');
    }
}
