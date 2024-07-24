<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;
use App\Traits\TranslateMethods;
 
class FeatureCategory extends Model
{
    use HasFactory, HasTranslations, SoftDeletes, LogsActivity, TranslateMethods;

    protected $guarded = [];
    public $translatable = ['title'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function getFeature()
    {
        return $this->hasMany(Feature::class, 'category', 'id');
    }
 
    // protected function title(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $this->toArray($value),
    //     );
    // }
 
 

}
