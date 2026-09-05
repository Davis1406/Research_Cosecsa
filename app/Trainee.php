<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trainee extends Model
{
    use SoftDeletes;

    public $table = 'trainees';

    protected $dates = [
        'enrollment_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'course_type',
        'institution',
        'registration_number',
        'country',
        'specialty',
        'enrollment_date',
        'notes',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function documents()
    {
        return $this->hasMany(\App\TraineeDocument::class);
    }

    public function scopeCourse($query, $courseType)
    {
        return $query->where('course_type', $courseType);
    }
}
