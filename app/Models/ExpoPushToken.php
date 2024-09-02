<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpoPushToken extends Model
{
    protected $fillable = ['user_id', 'expo_push_token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
