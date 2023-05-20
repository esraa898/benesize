<?php

use App\Http\Controllers\Api\BadgeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\Clients\AuthController;
use App\Http\Controllers\Api\Clients\GeneralController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SellerController;
use App\Http\Controllers\Api\SizeController;

use Illuminate\Support\Facades\Route;


////////////////////////////////// start  auth /////////////////////////////////////////////
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::get('profile', [AuthController::class, 'userProfile']);
Route::post('edit-profile', [AuthController::class, 'editProfile']);
Route::post('upload-image', [AuthController::class, 'uploadImage']);
Route::post('change-password', [AuthController::class, 'changePassword']);
Route::post('check-phone', [AuthController::class, 'checkPhone']);
Route::post('check-code', [AuthController::class, 'checkCode']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('remove-account', [AuthController::class, 'removeAccount']);
Route::post('custom-remove-account', [AuthController::class, 'customRemoveAccount']);

    Route::post('seller/register', [AuthController::class, 'sellerRegister']);


Route::middleware('auth.guard:api')->group(function () {

    ///////////categories/////////////
//    Route::get('/category', [CategoryController::class, 'index']);
//    Route::post('/category', [CategoryController::class, 'store']);
//    Route::get('/category/show', [CategoryController::class, 'show']);
//    Route::get('/category/edit', [CategoryController::class, 'edit']);
//    Route::post('/category/update', [CategoryController::class, 'update']);
//    Route::post('/category/destroy', [CategoryController::class, 'destroy']);


    /////////size//////////

//    Route::get("size", [SizeController::class, "index"]);
//    Route::post("size", [SizeController::class, "store"]);
//    Route::get("size/create", [SizeController::class, "create"]);
//    Route::get("size/edit", [SizeController::class, "edit"]);
//    Route::post("size/update", [SizeController::class, "update"]);
//    Route::post("size/destroy", [SizeController::class, "destroy"]);

    Route::resources([
        "badge" => BadgeController::class,
        "category" => CategoryController::class,
        "color" => ColorController::class,
        "product" => ProductController::class,
        "seller" => SellerController::class,
        "size" => SizeController::class,

    ]);

});


    /////////////////////////////////////////////
    ///                     info              ///
    /// /////////////////////////////////////////
Route::get('countries', [GeneralController::class, 'countries']);
Route::get('cities', [GeneralController::class, 'cities']);
Route::get('areas', [GeneralController::class, 'areas']);




    /////////////////////////////////////////////
    ///                     categories        ///
    /// /////////////////////////////////////////

//
//Route::get('/categories', [CategoryController::class, 'index']);
//Route::post('/categories', [CategoryController::class, 'store']);
//Route::get('/categories/{category}', [CategoryController::class, 'show']);
//Route::put('/categories/{category}', [CategoryController::class, 'update']);
//Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
//
//
