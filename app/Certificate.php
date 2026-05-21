<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'trainee_id',
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
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
