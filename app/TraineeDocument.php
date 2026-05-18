<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TraineeDocument extends Model
{
    protected $fillable = ['trainee_id', 'document_type', 'original_name', 'file_path'];

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }
}
