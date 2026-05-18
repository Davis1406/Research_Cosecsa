<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TraineeDocumentComment extends Model
{
    protected $fillable = ['trainee_document_id', 'user_id', 'comment'];

    public function document()
    {
        return $this->belongsTo(TraineeDocument::class, 'trainee_document_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
