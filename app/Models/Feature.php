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

class Feature extends Model
{
    use HasFactory, HasTranslations, SoftDeletes, LogsActivity, TranslateMethods;

    protected $guarded = [];
    public $translatable = ['title'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }


    public function getCategory()
    {
        return $this->hasOne(FeatureCategory::class, 'id', 'category');
    }

    public static function booted()
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('sort', 'ASC');
        });
    }

  
}
