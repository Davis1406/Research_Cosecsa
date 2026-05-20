<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discussion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'user_id',
        'schedule_id',
        'is_general',
    ];

    protected $casts = [
        'is_general' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class)->withTrashed();
    }

    public function replies()
    {
        return $this->hasMany(DiscussionReply::class);
    }

    public function latestReply()
    {
        return $this->hasOne(DiscussionReply::class)->latestOfMany();
    }
}
