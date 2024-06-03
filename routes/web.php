<?php

use App\Http\Controllers\Backend\AdminsController;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\CharterController;
 
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DestinationController;
use App\Http\Controllers\Backend\FeatureCategoryController;
use App\Http\Controllers\Backend\FeatureController;
 
 
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\TypeController;
use App\Http\Controllers\Backend\UsersController;
 
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
 

Route::get('/', [HomeController::class, 'redirectAdmin'])->name('index');
Route::get('/home', [HomeController::class, 'index'])->name('home');

/**
 * Admin routes
 */
Route::group(['prefix' => 'admin'], function () {
 

    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('roles', RolesController::class, ['names' => 'admin.roles']);
    Route::resource('users', UsersController::class, ['names' => 'admin.users']);
    Route::resource('admins', AdminsController::class, ['names' => 'admin.admins']);


    // Login Routes
    Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login/submit', [LoginController::class, 'login'])->name('admin.login.submit');

    // Logout Routes
    Route::post('/logout/submit',  [LoginController::class, 'logout'])->name('admin.logout.submit');

    // Forget Password Routes
    // Route::get('/password/reset',  [ForgetPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    // Route::post('/password/reset/submit', [ForgetPasswordController::class, 'reset'])->name('admin.password.update');
    Route::post('password-update/{id}/update', [AdminsController::class, 'passwordUpdate'])->name('password.update');
    Route::get('password-update/{id}', [AdminsController::class, 'passwordEdit'])->name('password.edit');

    Route::resource('destination', DestinationController::class, ['names' => 'admin.destination']);
    Route::get('/destination/delete/{id?}', [DestinationController::class, 'delete'])->name('admin.destination.delete');

    Route::resource('type', TypeController::class, ['names' => 'admin.type']);
    Route::get('/type/delete/{id?}', [TypeController::class, 'delete'])->name('admin.type.delete');

    Route::resource('feature', FeatureController::class, ['names' => 'admin.feature']);
    Route::get('/feature/delete/{id?}', [FeatureController::class, 'delete'])->name('admin.feature.delete');

    Route::resource('featurecategory', FeatureCategoryController::class, ['names' => 'admin.featurecategory']);
    Route::get('/featurecategory/delete/{id?}', [FeatureCategoryController::class, 'delete'])->name('admin.featurecategory.delete');


    Route::resource('charter', CharterController::class, ['names' => 'admin.charter']);
    Route::get('/charter/delete/{id?}', [CharterController::class, 'delete'])->name('admin.charter.delete');
    Route::post('/charter/photoDelete', [CharterController::class, 'photoDelete'])->name('admin.charter.photoDelete');

});
