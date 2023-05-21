<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\GeneralController;
use GuzzleHttp\Middleware;



Route::post('/checkPhoneNumber',[AuthController::class, 'checkPhone']);
Route::post('/verificationCodeNumber',[AuthController::class, 'checkCode']);
Route::post('/savePassword',[AuthController::class, 'save_password']);
Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
Route::post('/changePassword', [AuthController::class, 'changePassword']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);

Route::post('/editProfile', [AuthController::class, 'editProfile']);
Route::post('/removeAccount', [AuthController::class, 'removeAccount']);
Route::get('/profile', [AuthController::class, 'userProfile']);

Route::post('uploadImage', [AuthController::class, 'uploadImage']);

    /////////////////////////////////////////////
    ///                     info              ///
    /// /////////////////////////////////////////
    Route::get('countries', [GeneralController::class, 'countries']);
    Route::get('cities', [GeneralController::class, 'cities']);
    Route::get('areas', [GeneralController::class, 'areas']);

   
    Route::middleware('auth.guard:api')->group(function () {
        Route::get('sliders',[HomeController::class,'sliders']);
    });

