<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Charter;
use App\Models\CharterPhoto;
use App\Models\Destination;
use App\Models\ExpoPushToken;
use App\Models\Feature;
use App\Models\FeatureCategory;
use App\Models\Notification;
use App\Models\Type;
use App\Models\User;
use App\Models\Wishlist;
use App\Notifications\ExpoPushNotification;
use Aws\CommandPool;
use Aws\Exception\AwsException;
use Aws\ResultInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CharterController extends Controller
{

    function sendPushNotification(Request $request)
    {


        //    // Hangi kullanıcılara bildirim gönderileceğini belirleyin (örneğin, tüm kullanıcılar)
        //    $users = User::whereHas('expoPushTokens')->get();

        //    if ($users->isEmpty()) {
        //        return response()->json(['error' => 'No users with valid push tokens found'], 404);
        //    }

        //    $notification = new ExpoPushNotification(
        //        'Toplu Mesaj', // Bildirim başlığı
        //        'Birden fazla kullanıcıya gönderilen bir mesaj', // Bildirim içeriği
        //        ['customData' => 'Ekstra veri'] // Ekstra veri (opsiyonel)
        //    );

        //    // Toplu bildirim gönderimi
        //    $notification->sendBulkNotifications($users);

        //    return response()->json(['success' => 'Bulk notifications sent!']);



        $user = User::find($request->user_id);

        if (!$user || $user->expoPushTokens->isEmpty()) {
            return response()->json(['error' => 'User not found or does not have push tokens'], 404);
        }

        $notification = new ExpoPushNotification(
            'Yeni Mesaj', // Bildirim başlığı
            'Bir mesajınız var!', // Bildirim içeriği
            ['customData' => 'Ekstra veri'] // Ekstra veri (opsiyonel)
        );
        $notification->sendExpoNotification($user);

        return response()->json(['success' => 'Notification sent!']);
    }

    public function saveToken(Request $request)
    {
        // Verileri doğrula
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'expo_push_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'details' => $validator->errors()], 400);
        }

        $user_id = $request->user_id;
        $expo_push_token = $request->expo_push_token;

        // Kullanıcıyı bul
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Yeni token'ı ekle
        ExpoPushToken::updateOrCreate(
            ['expo_push_token' => $expo_push_token],
            ['user_id' => $user_id]
        );

        return response()->json(['success' => 'Token saved or updated!']);
    }


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

        $charter = Charter::with(['getPrice', 'getBooking.getUser', 'getBooking.getCharter.getUser', 'getSetting'])->where('user', $request->user()->id)->get();
        return response()->json([
            'charter' => $charter,
        ]);
    }

    public function getBookingAgency(Request $request)
    {

        $booking = Booking::with(['getCharter.getUser', 'getUser'])->where('user', $request->user()->id)->latest()->get();
        return response()->json([
            'booking' => $booking,
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
        $photo = CharterPhoto::find($request->id);

        $sourceBucket = env('AWS_BUCKET');
        $sourceKeyname = $photo->path;
        $targetKeyname = "deleted/" . (explode('/', $photo->path))[1];
        $targetBucket = env('AWS_BUCKET');

        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),

        ]);

        $s3->copyObject([
            'Bucket' => $targetBucket,
            'Key' => "$targetKeyname",
            'CopySource' => "$sourceBucket/$sourceKeyname",
        ]);

        $s3->deleteObject(array(
            'Bucket' => $sourceBucket,
            'Key'    => $sourceKeyname
        ));

        if ($photo->delete()) {

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
    public function getBookingById(Request $request)
    {

        $booking = Booking::with(['getUser', 'getCharter'])->where('id', request()->get('id'))->first();


        return response()->json([
            'booking' => $booking

        ]);
    }



    public function createCharter(Request $request)
    {


        $charter = new Charter();

        $charter->fill([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'destination' => $request->destination,
            'user' => $request->user()->id,
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

        foreach ($request["image"] as $photo) {


            $charter->getPhotos()->create([
                'path' =>  'charter/' . $photo,
                'highlighted' =>  $photo == $request->highlighted ? 1 : 0
            ]);
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

        if ($request['image']) {
            foreach ($request["image"] as $photo) {
                $charter->getPhotos()->create([
                    'path' =>  'charter/' . $photo,

                ]);
            }
        }



        if ($request['highlighted']) {

            $prevHighlighted = $charter->getPhotos()->where('highlighted', 1)->first();


            if ($prevHighlighted && $prevHighlighted->id != $request['highlighted']) {
                $prevHighlighted->update(["highlighted" => 0]);
            }

            $nextHighlighted = CharterPhoto::find($request['highlighted']);
            $nextHighlighted->update(["highlighted" => 1]);
        } else {
            $nextHighlighted = $charter->getPhotos()->first();
            $nextHighlighted->update(["highlighted" => 1]);
        }

        // if ($request['image']) {

        //     foreach ($request['image'] as $photo) {

        //         $image = $photo['base64'];
        //         $imageName = uniqid() . '.' . (explode('/', $photo['mimeType']))[1];
        //         \File::put(public_path() . '/data/uploads/charter/' . $imageName, base64_decode($image));

        //         $charter->getPhotos()->create([
        //             'path' =>  'data/uploads/charter/' . $imageName
        //         ]);
        //     }
        // }





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


        $user = User::find($charter->user);

        if (!$user || $user->expoPushTokens->isEmpty()) {
            return response()->json(['error' => 'User not found or does not have push tokens'], 404);
        }

        $notification = new ExpoPushNotification(
            'Yeni Rezervasyon Talebi Oluşturuldu', // Bildirim başlığı
            $charter->title . ' adlı tekne için rezervasyon talebi oluşturuldu.', // Bildirim içeriği
            ['customData' => 'Ekstra veri'] // Ekstra veri (opsiyonel)
        );
        $notification->sendExpoNotification($user);


        $notification = new Notification();

        $notification->fill([
            'title' => "Rezervasyon Talebi Oluşturuldu",
            'description' => $charter->title . ' adlı tekne için rezervasyon talebi oluşturuldu.',
            'subject_id' => $charter->getBooking()->latest()->first()->id,
            'user' => $charter->user,
            'type' => 1
        ]);
        $notification->save();

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
        $notifiArr = [];


        $booking = Booking::with(['getUser', 'getCharter.getUser'])->where('id', $request->id)->first();
        $current_status = $booking->status;
        $booking->fill([
            "status" => $request->status
        ]); 
        $booking->save();

        if ($request->status == 1) {
            $str = "TALEBİ ONAYLANDI";
            array_push($notifiArr, $booking->user);
        } elseif ($request->status == 2) {
            $str = "TALEBİ REDDEDİLDİ";
       
            array_push($notifiArr, $booking->user);
        } elseif ($request->status == 3 && $current_status == 0) {
            $str = "TALEBİ İPTAL EDİLDİ";
     
            array_push($notifiArr, $booking->getCharter->user);
        } elseif ($request->status == 3 && $current_status == 1) {
            $str = "İPTAL EDİLDİ";
            array_push($notifiArr, $booking->user);
  
        }




        foreach (array_unique($notifiArr) as $item) {

            $user = User::find($item);

            if (!$user || $user->expoPushTokens->isEmpty()) {
                return response()->json(['error' => 'User not found or does not have push tokens'], 404);
            }

            $notification = new ExpoPushNotification(
                'Rezervasyon ' . Str::title($str), // Bildirim başlığı
                $booking->getCharter->title . ' adlı tekne için '. Carbon::parse($booking->check_in)->format('d-m-Y') .' tarihli rezervasyon ' . Str::lower($str), // Bildirim içeriği
                [
                    'route' => 'BookingDetail',
                    'data' =>  json_encode(["booking"=>$booking]),
                ]
            );
            $notification->sendExpoNotification($user);

            $notifi = new Notification();
            $notifi->fill([
                'title' => 'Rezervasyon ' . Str::title($str),
                'description' => $booking->getCharter->title . ' adlı tekne için rezervasyon ' . Str::lower($str),
                'subject_id' => $booking->getCharter->id,
                'user' => $item,
                'type' => 1
            ]);
            $notifi->save();
        }


   
        return response()->json([
            'booking' => $booking,
         
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


        $start_date = Carbon::parse($request->startDate)->format("Y-m-d");
        $end_date = Carbon::parse($request->endDate)->format("Y-m-d");
        $period = CarbonPeriod::create($start_date, $end_date);

        $start_date_month = Carbon::parse($request->startDate)->format("n");
        $priceBegin = $request->priceBegin == 0 ? 1 : $request->priceBegin;
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


    public function addWishlist(Request $request)
    {
        $check = Wishlist::where('user', $request->user()->id)->where('charter', $request->charter)->first();

        if ($check === null) {
            // user doesn't exist



            $wishlist = new Wishlist();
            $wishlist->fill([
                'user' => $request->user()->id,
                'charter' => $request->charter
            ]);
            $wishlist->save();
        }
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function getWishlist(Request $request)
    {

        $wishlist = Wishlist::where('user', $request->user()->id)->get();


        return response()->json([
            'wishlist' => $wishlist
        ]);
    }
    public function getWishlistWithCharter(Request $request)
    {

        $wishlist = Wishlist::with(['getCharter.getPrice', 'getCharter.getFeature', 'getCharter.getSetting'])->where('user', $request->user()->id)->get();


        return response()->json([
            'wishlist' => $wishlist
        ]);
    }

    public function deleteWishlist(Request $request)
    {

        $wishlist = Wishlist::where('user', $request->user()->id)->where('charter', $request->charter)->first();
        if ($wishlist) {

            $wishlist->delete();

            return json_encode([
                'status' => 'success'
            ]);
        } else {
            return json_encode([
                'status' => 'error'
            ]);
        }
    }
}
