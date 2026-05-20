<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function reviewers()
    {
        return $this->belongsToMany(User::class, 'presentation_reviewers')->withTimestamps();
    }

    public function getFullPathAttribute()
    {
        return storage_path('app/public/' . $this->file_path);
    }

    public function getDownloadUrlAttribute()
    {
        return Storage::disk('public')->url($this->file_path);
    }
}
