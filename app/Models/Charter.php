<?php

namespace App\Models;

use App\Traits\TranslateMethods;
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
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
