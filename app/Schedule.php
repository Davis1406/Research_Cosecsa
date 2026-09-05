<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\TrainingMaterial;

class Schedule extends Model
{
    use SoftDeletes;

    public $table = 'schedules';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'subtitle',
        'course_type',
        'day_number',
        'date',
        'start_time',
        'end_time',
        'location',
        'speaker_id',
        'is_completed',
        'completed_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function speaker()
    {
        return $this->belongsTo(Speaker::class, 'speaker_id');
    }

    public function materials()
    {
        return $this->belongsToMany(TrainingMaterial::class, 'schedule_training_material');
    }

    /**
     * Scope: only sessions suitable for discussions or quizzes.
     * Excludes breaks, administrative ceremonies, wrap-ups and recaps.
     */
    public function scopeDiscussable($query)
    {
        $exclude = [
            'break',        // Morning Tea Break, Lunch Break, Afternoon Tea Break
            'registration', // Registration and Pre-Course Assessment
            'welcome',      // Introductions and Welcome Remarks
            'opening',      // Official Opening
            'wrap',         // Wrap-Up of the Day
            'recap',        // Recap of Day X / Recap of Previous Day
            'closure',      // Course Closure and Certification
        ];

        foreach ($exclude as $kw) {
            $query->where('title', 'not like', "%{$kw}%");
        }

        return $query;
    }

    public function scopeCourse($query, $courseType)
    {
        return $query->where('course_type', $courseType);
    }
}
