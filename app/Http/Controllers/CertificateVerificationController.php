<?php

namespace App\Http\Controllers;

use App\Certificate;

class CertificateVerificationController extends Controller
{
    /**
     * Public "COSECSA Verified" page — reached by scanning the QR code printed
     * on a certificate. No auth: anyone scanning the QR should be able to
     * confirm the certificate is genuine. A valid token proves the certificate
     * exists and shows who it was issued to, for which course.
     */
    public function show(string $token)
    {
        $certificate = Certificate::with(['trainee'])
            ->where('verification_token', $token)
            ->firstOrFail();

        return view('certificates.verify', compact('certificate'));
    }
}