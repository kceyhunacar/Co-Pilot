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

class Charter extends Model
{

    use HasFactory, HasTranslations, SoftDeletes, LogsActivity, TranslateMethods;

    protected $guarded = [];
    public $translatable = ['description'];
    // protected $with = ['getType'];
    

    protected $appends = ['photo','destination_title','type_title'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    
    public function getUser()
    {
        return $this->hasOne(User::class, 'id', 'user');
    }

    public function getDestination()
    {
        return $this->hasOne(Destination::class, 'id', 'destination');
    }
    public function getType()
    {
        return $this->hasOne(Type::class, 'id', 'type')->orderBy('sort', 'desc');
    }
    public function getFeature()
    {
        return $this->hasMany(CharterFeature::class, 'charter', 'id');
    }
    public function getBooking()
    {
        return $this->hasMany(Booking::class, 'charter', 'id');
    }
    public function getPrice()
    {
        return $this->hasOne(CharterPrice::class, 'charter', 'id');
    }
    public function getSetting()
    {
        return $this->hasOne(CharterSetting::class, 'charter', 'id');
    }
    public function getPhotos()
    {
        return $this->hasMany(CharterPhoto::class, 'charter', 'id');
    }

    public function getPhotoAttribute()
    {
        return $this->getPhotos->where('highlighted', 1)->first()->path;
    }

    public function getDestinationTitleAttribute()
    {
        return $this->getDestination->first()->title;
    }

    public function getTypeTitleAttribute()
    {
        return $this->getType->first()->title;
    }

    // public function mainPhoto()
    // {
    //     return $this->getPhotos()->where('highlighted', 1)->first();
    // }
    // public static function booted()
    // {
    //     static::addGlobalScope('order', function (Builder $builder) {
    //         $builder->getPhotos()->where('highlighted', 1)->first();
    //     });
    // }


    // public function scopeSortType($query, $type,$month="0")
    // {

    //     switch ($type) {
    //         case 'default':
    //             return $query->orderBy('created_at', 'DESC');
    //             break;
    //         case 'low_price':
    //             return $query->orderBy($month, 'ASC');
    //             break;
    //         case 'high_price':
    //             return $query->orderBy($month, 'DESC');
    //             break;

    //         default:
    //             return $query;
    //             break;
    //     }
    // }
}
