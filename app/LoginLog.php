<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'ip_address', 'logged_in_at'];

    protected $dates = ['logged_in_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
