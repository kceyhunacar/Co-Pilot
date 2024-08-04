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

class Notification extends Model
{
    use HasFactory,HasTranslations,SoftDeletes, LogsActivity,TranslateMethods;

    protected $guarded = [];
    public $translatable = ['title'];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }


    public static function booted()
    {
        static::addGlobalScope('created_at', function (Builder $builder) {
            $builder->orderBy('created_at','DESC');
        });
    }
}
