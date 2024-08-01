<?php

namespace App\Models;

use App\Traits\TranslateMethods;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Destination extends Model
{
    use HasFactory,HasTranslations,SoftDeletes, LogsActivity,TranslateMethods;

    protected $guarded = [];
    public $translatable = ['title'];


    
    public static function booted()
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('sort','ASC');
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }
}
