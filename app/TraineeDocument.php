<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TraineeDocument extends Model
{
    protected $fillable = ['trainee_id', 'document_type', 'original_name', 'title', 'file_path'];

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }

    public function comments()
    {
        return $this->hasMany(TraineeDocumentComment::class)->with('user')->latest();
    }

    public function getFullPathAttribute()
    {
        return storage_path('app/public/' . $this->file_path);
    }

    public function getDownloadUrlAttribute()
    {
        return '/research/storage/' . $this->file_path;
    }
}
