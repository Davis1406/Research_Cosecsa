<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscussionReply extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'discussion_id',
        'user_id',
        'body',
    ];

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
