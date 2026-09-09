<?php

namespace App\Http\Controllers;

use App\Certificate;

class CertificateViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Shared certificate preview — reuses the existing preview blade.
     * Admin/Facilitator can view any certificate; a Trainee can only view
     * their own (this is how a trainee reaches their auto-issued online
     * certificate — the admin/facilitator preview routes are staff-gated).
     */
    public function show(Certificate $certificate)
    {
        $user = auth()->user();
        $roles = $user->roles->pluck('title')
            ->map(fn($t) => strtolower(str_replace(' ', '-', $t)))
            ->toArray();

        $isStaff = !empty(array_intersect($roles, ['admin', 'super-admin', 'facilitator', 'lead-facilitator']));
        $isOwner = $certificate->trainee && $certificate->trainee->user_id === $user->id;

        abort_unless($isStaff || $isOwner, 403);

        $certificate->load(['trainee', 'issuedBy']);

        return view('facilitator.certificates.preview', compact('certificate'));
    }
}
