<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CharterPhoto extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];
     

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public static function booted()
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('highlighted','DESC');
        });
    }


    
}
