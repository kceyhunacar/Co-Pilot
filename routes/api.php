<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CharterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/getFeatures', [CharterController::class, 'getFeatures']);
Route::get('/getDestinations', [CharterController::class, 'getDestinations']);
Route::get('/getTypes', [CharterController::class, 'getTypes']);
Route::get('/getQuantityInput', [CharterController::class, 'getQuantityInput']);
Route::post('/sendPushNotification', [CharterController::class, 'sendPushNotification']);
Route::post('/save-token', [CharterController::class, 'saveToken']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
// logout is a protected endpoint
Route::get('/getCharterByIdWithoutUser', [CharterController::class, 'getCharterByIdWithoutUser']);
Route::get('/getCharterHighlighted', [CharterController::class, 'getCharterHighlighted']);
Route::get('/getBookingById', [CharterController::class, 'getBookingById']);
Route::post('/getCharterFiltered', [CharterController::class, 'getCharterFiltered']);
Route::group(["middleware" => ['auth:sanctum']], function () {
    Route::post('/createCharter', [CharterController::class, 'createCharter']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/updateUser', [AuthController::class, 'updateUser']);
    Route::post('/updatePassword', [AuthController::class, 'updatePassword']);
    Route::get('/getCharter', [CharterController::class, 'getCharter']);
    Route::get('/getCharterWithHighlighted', [CharterController::class, 'getCharterWithHighlighted']);
    Route::get('/getCharterById', [CharterController::class, 'getCharterById']);
    Route::get('/getNotification', [CharterController::class, 'getNotification']);

    Route::get('/getCharterWithPriceBooking', [CharterController::class, 'getCharterWithPriceBooking']);
    Route::post('/charterPhotoDelete', [CharterController::class, 'charterPhotoDelete']);
    Route::post('/updateCharter', [CharterController::class, 'updateCharter']);
    Route::post('/createBooking', [CharterController::class, 'createBooking']);
    Route::post('/bookingStatus', [CharterController::class, 'bookingStatus']);
    Route::post('/deleteWishlist', [CharterController::class, 'deleteWishlist']);
    Route::post('/addWishlist', [CharterController::class, 'addWishlist']);
    Route::get('/getBookingAgency', [CharterController::class, 'getBookingAgency']);
    Route::get('/getWishlist', [CharterController::class, 'getWishlist']);
    Route::get('/getWishlistWithCharter', [CharterController::class, 'getWishlistWithCharter']);
});
