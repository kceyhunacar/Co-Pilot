<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Booking extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];
     

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, 'id', 'user');
    }
    public function getCharter()
    {
        return $this->hasOne(Charter::class, 'id', 'charter');
    }


}
