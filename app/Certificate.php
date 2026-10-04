<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $fillable = [
        'trainee_id',
        'course_type',
        'event_name',
        'venue',
        'event_date',
        'issued_by',
        'course_name',
        'org_name',
        'sig1_name',
        'sig1_title',
        'sig1_path',
        'sig2_name',
        'sig2_title',
        'sig2_path',
        'logo_path',
        'logo2_path',
        'logo3_path',
        'stamp_path',
        'generated_at',
        'auto_generated',
        'verification_token',
    ];

    protected $casts = [
        'generated_at'   => 'datetime',
        'auto_generated' => 'boolean',
    ];

    protected static function booted()
    {
        // Every certificate gets a unique, unguessable verification token so
        // its QR code can be pointed at a public "COSECSA Verified" page.
        static::creating(function ($certificate) {
            if (empty($certificate->verification_token)) {
                $certificate->verification_token = Str::random(32);
            }
        });
    }

    /** Public URL scanned from the certificate QR code. */
    public function verificationUrl(): string
    {
        return route('certificate.verify', $this->verification_token);
    }

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
