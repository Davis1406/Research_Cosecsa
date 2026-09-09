<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Tracks that a trainee (user) has viewed a given training material — the
 * "materials viewed" half of the online-course completion check.
 */
class TrainingMaterialView extends Model
{
    protected $fillable = [
        'user_id',
        'training_material_id',
        'viewed_at',
    ];

    protected $dates = ['viewed_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function material()
    {
        return $this->belongsTo(TrainingMaterial::class, 'training_material_id');
    }
}
