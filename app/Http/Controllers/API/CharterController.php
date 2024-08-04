<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Charter;
use App\Models\CharterPhoto;
use App\Models\Destination;
use App\Models\Feature;
use App\Models\FeatureCategory;
use App\Models\Notification;
use App\Models\Type;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CharterController extends Controller
{

    public function getFeatures()
    {


        $feature_category = FeatureCategory::whereHas('getFeature', function ($query) {
            return $query->where('type', '=', 2);
        })->with(['getFeature'])->get();


        return response()->json([
            'feature_category' => $feature_category,
        ]);
    }



    public  function  getCharter(Request $request)
    {

        $charter = Charter::with(['getType', 'getDestination', 'getPrice', 'getFeature', 'getPhotos'])->where('user', $request->user()->id)->get();
        return response()->json([
            'charter' => $charter,
        ]);
    }

    public function getCharterWithPriceBooking(Request $request)
    {

        $charter = Charter::with(['getPrice', 'getBooking.getUser', 'getSetting'])->where('user', $request->user()->id)->get();
        return response()->json([
            'charter' => $charter,
        ]);
    }


    public function getBookedDatesById(Request $request)
    {

        $charter = Booking::where('charter', $request->id)->pluck('dates')->toArray();
        return response()->json([
            'charter' => $charter,
        ]);
    }

    public function charterPhotoDelete(Request $request)
    {
        $model = CharterPhoto::find($request->id);

        if ($model->forceDelete()) {
            @unlink(public_path($model->path));
            return json_encode([
                'status' => 'success'
            ]);
        } else {
            return json_encode([
                'status' => 'error'
            ]);
        }
    }


    public function getCharterById(Request $request)
    {

        $charter = Charter::with(['getSetting', 'getPrice', 'getFeature', 'getPhotos'])->where('user', $request->user()->id)->where('id', $request->get('id'))->first();

        return response()->json([
            'charter' => $charter,
        ]);
    }

    public function getCharterByIdWithoutUser(Request $request)
    {

        $charter = Charter::with(['getDestination', 'getType', 'getSetting', 'getPrice', 'getFeature.getCategory.getCategory', 'getPhotos', 'getBooking'])->where('id', $request->get('id'))->first();

        return response()->json([
            'charter' => $charter
        ]);
    }

    public function getCharterHighlighted()
    {

        $charter = Charter::with(['getSetting', 'getPrice', 'getFeature', 'getPhotos', 'getDestination', 'getType'])->where('highlighted', 1)->get();

        return response()->json([
            'charter' => $charter,
        ]);
    }


    public function getQuantityInput()
    {


        $feature_category = Feature::where('type', 1)->get();


        return response()->json([

            'input' => $feature_category,


        ]);
    }
    public function getTypes()
    {

        $type = Type::where('status', 1)->get();

        return response()->json([
            'type' => $type
        ]);
    }
    public function getDestinations()
    {

        $destination = Destination::where('status', 1)->get();

        return response()->json([
            'destination' => $destination

        ]);
    }



    public function createCharter(Request $request)
    {


        $charter = new Charter();

        $destination = Destination::where('title', "LIKE", '%' . $request->destination . '%')->first();
        $type = Type::where('title', "LIKE", '%' . $request->type . '%')->first();

        $charter->fill([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $type->id,
            'user' => $request->user()->id,
            'destination' => $destination->id,
            'status' => 2,
        ]);
        $charter->save();

        foreach ($request['feature'] as  $value) {

            $charter->getFeature()->create([
                'charter' => $charter->id,
                'feature' => $value['id'],
                'value' => $value['value'],

            ]);
        }

        $charter->getSetting()->create([
            'quick' => $request->setting['quick'],
            'notification' => $request->setting['notification'],
            'min_day' => $request->setting['minDay'],
            'status' => $request->setting['status'],
            'contact' => $request->setting['contact'],


        ]);


        foreach ($request->price as $item) {
            $prices[$item['id']] = $item['value'];
        }

        $charter->getPrice()->create($prices);

        if ($request["image"]) {

            foreach ($request["image"] as $photo) {

                if ($photo['fileName'] == $request->highlighted) {
                    $highlighted = 1;
                } else {
                    $highlighted = 0;
                }

                $image = $photo['base64'];
                $imageName = uniqid() . '.' . (explode('/', $photo['mimeType']))[1];
                \File::put(public_path() . '/data/uploads/charter/' . $imageName,   base64_decode($image));

                $charter->getPhotos()->create([
                    'path' =>  'data/uploads/charter/' . $imageName,
                    'highlighted' =>  $highlighted
                ]);
            }
        }


        return response()->json([
            'createCharter' => $charter->id
        ]);
    }



    public function updateCharter(Request $request)
    {

        $charter = Charter::with(['getPrice', 'getFeature', 'getPhotos'])->where('id', $request->id)->first();

        $charter->fill([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'destination' => $request->destination,
        ]);


        $charter->save();


        foreach ($request['feature'] as $value) {


            if (is_bool($value['value'])) {
                if ($value['value'] == true) {
                    $val = 1;
                } else if ($value['value'] == false) {
                    $val = 0;
                }
            } else {
                $val = $value['value'];
            }

            $charter->getFeature()->updateOrCreate(
                [
                    'feature' => $value['id'],
                    'charter' => $charter->id,
                ],
                [

                    'feature' => $value['id'],
                    'value' => $val,

                ]
            );
        }

        $prices = [];

        foreach ($request["price"] as $item) {
            $prices[$item['id']] = $item['value'];
        }

        $charter->getPrice()->updateOrCreate(['charter' => $charter->id], $prices);
        $charter->getSetting()->updateOrCreate(
            ['charter' => $charter->id],
            [
                'quick' => $request->setting['quick'],
                'notification' => $request->setting['notification'],
                'min_day' => $request->setting['minDay'],
                'status' => $request->setting['status'],
                'contact' => $request->setting['contact'],
            ]
        );

        if ($request['highlighted']) {

            $prevHighlighted = ($charter->getPhotos()->where('highlighted', 1)->first());
            if ($prevHighlighted && $prevHighlighted->id != $request['highlighted']) {
                $charter->getPhotos()->update(["highlighted" => 0]);
            }
            $nextHighlighted = CharterPhoto::find($request->highlighted);
            $nextHighlighted->update(["highlighted" => 1]);
        }

        if ($request['image']) {

            foreach ($request['image'] as $photo) {

                $image = $photo['base64'];
                $imageName = uniqid() . '.' . (explode('/', $photo['mimeType']))[1];
                \File::put(public_path() . '/data/uploads/charter/' . $imageName, base64_decode($image));

                $charter->getPhotos()->create([
                    'path' =>  'data/uploads/charter/' . $imageName
                ]);
            }
        }

        return response()->json([
            'createCharter' => $request['highlighted']
        ]);
    }





    public function createBooking(Request $request)
    {

        $charter = Charter::where('id', $request->id)->first();


        $dates = [];
        $current = strtotime(Carbon::parse($request->check_in)->format('Y-m-d'));
        $date2 = strtotime(Carbon::parse($request->check_out)->format('Y-m-d'));
        $stepVal = '+1 day';
        while ($current <= $date2) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime($stepVal, $current);
        }



        $charter->getBooking()->create([
            'check_in' => Carbon::parse($request->check_in)->format('Y-m-d'),
            'check_out' => Carbon::parse($request->check_out)->format('Y-m-d'),
            'price' => $request->price,
            'total_price' => $request->total_price,
            'country_code' => $request->country_code,
            'user' => $request->user()->id,
            'phone' => $request->phone,
            'name' => $request->name,
            'pax' => $request->pax,
            'dates' => json_encode($dates),
        ]);

        $notifiArr = [];
        array_push($notifiArr, $charter->user);
        array_push($notifiArr, $request->user()->id);
        foreach (array_unique($notifiArr) as $item) {

            $notification = new Notification();

            $notification->fill([
                'title' => "Rezervasyon Talebi Oluşturuldu",
                'description' => $charter->title . ' adlı tekne için rezervasyon talebi oluşturuldu.',
                'subject_id' => $charter->getBooking()->latest()->first()->id,
                'user' => $item,
                'type' => 1
            ]);
            $notification->save();
        }




        return response()->json([
            'createCharter' => $dates
        ]);
    }



     
        //  0=>beklemede
        //  1=>onaylandı 
        //  2=>reddedildi
        //  3=>iptal edildi 
        

    public function bookingStatus(Request $request)
    {

        $booking = Booking::with(['getUser','getCharter'])->where('id', $request->id)->first();
        $booking->fill([
            "status" => $request->status
        ]);
        $booking->save();

         if($request->status==1){
            $str= "onaylandı";

         }elseif($request->status==2){
            $str= "reddedildi";
         }elseif($request->status==3){
            $str= "iptal edildi ";
         }

      

        $notifiArr = [];
        array_push($notifiArr, $booking->getCharter->user);
        array_push($notifiArr, $booking->user);

        foreach (array_unique($notifiArr) as $item) {

            $notification = new Notification();
            $notification->fill([
                'title' => "Rezervasyon talebi ".$str,
                'description' => $booking->getCharter->title . ' adlı tekne için rezervasyon talebi '.$str,
                'subject_id' => $booking->getCharter->id,
                'user' => $item,
                'type' => 1
            ]);
            $notification->save();
        }


 
        return response()->json([
            'booking' => $booking
        ]);
    }


    public  function  getCharterFiltered(Request $request)
    {


        $filter = [];

        if ($request->destination != null && $request->destination != 0) {
            array_push($filter, ['destination', '=', $request->destination]);
        }


        if ($request->type != null && $request->type != 0) {
            array_push($filter, ['type', '=', $request->type]);
        }

        // if ($request->pax != null && $request->type != 0) {
        //     array_push($filter, ['type', '=', $request->type]);
        // }


        if ($request->priceBegin != null) {
            array_push($filter, [Carbon::parse($request->startDate)->format("n"), '>', ($request->get('min') / Cache::get('rates')['EUR']) - 1]);
        }
        if ($request->get('max') != null && $request->get('max') < 500000) {
            array_push($filter, [$price, '<', ($request->get('max') / Cache::get('rates')['EUR']) + 1]);
        }

        $start_date = Carbon::parse($request->startDate)->format("Y-m-d");
        $end_date = Carbon::parse($request->endDate)->format("Y-m-d");
        $period = CarbonPeriod::create($start_date, $end_date);

        $start_date_month = Carbon::parse($request->startDate)->format("n");
        $priceBegin = $request->priceBegin;
        $priceEnd = $request->priceEnd;
        $pax = $request->pax;

        $charter = Charter::with(['getType', 'getBooking', 'getDestination', 'getPrice', 'getFeature', 'getPhotos', 'getSetting'])

            ->where($filter)

            ->whereDoesntHave('getBooking', function ($q) use ($period) {

                foreach ($period as $date) {

                    $q->where('dates', "LIKE", '%' . Carbon::parse($date)->format("Y-m-d") . '%');
                }
            })

            ->whereHas('getPrice', function ($q) use ($start_date_month, $priceBegin, $priceEnd) {


                $q->whereBetween($start_date_month, [$priceBegin, $priceEnd]);
            })

            ->whereHas('getFeature', function ($q) use ($pax) {


                $q->where('feature', 22)->where('value', '>=', $pax);
            })

            ->get();




        return response()->json([
            'charter' => $charter,
        ]);
    }


    public function getNotification(Request $request)
    {

        $notification = Notification::where('user', $request->user()->id)->get();

        return response()->json([
            'notification' => $notification

        ]);
    }






}
