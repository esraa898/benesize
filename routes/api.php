<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\SizeController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\SubCategoryController;



Route::post('/checkPhoneNumber',[AuthController::class, 'checkPhone']);
Route::post('/verificationCodeNumber',[AuthController::class, 'checkCode']);
Route::post('/savePassword',[AuthController::class, 'save_password']);
Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
Route::post('/changePassword', [AuthController::class, 'changePassword']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/refresh', [AuthController::class, 'refresh']);

Route::post('/editProfile', [AuthController::class, 'editProfile']);
Route::post('/removeAccount', [AuthController::class, 'removeAccount']);
Route::get('/profile', [AuthController::class, 'userProfile']);

Route::post('/uploadImage', [AuthController::class, 'uploadImage']);

Route::post('/product_upload_Image',[ProductController::class,'addMedia']);

Route::post('/support', [SupportController::class, 'store']);

    /////////////////////////////////////////////
    ///                     info              ///
    /// /////////////////////////////////////////
Route::get('/countries', [GeneralController::class, 'countries']);
Route::get('/cities', [GeneralController::class, 'cities']);
Route::get('/areas', [GeneralController::class, 'areas']);



// Route::get('/sliders',[HomeController::class,'sliders']);
Route::get('/products',[ProductController::class,'index']);
Route::get('/categories',[CategoryController::class,'index']);
Route::get('/color',[ColorController::class,'index']);
Route::get('/size',[SizeController::class,'index']);


Route::post('/home',[HomeController::class,'get_home_info']);
Route::get('/product/{id}',[ProductController::class,'productDetail']);
Route::get('/productFilters',[HomeController::class,'product_filters']);



Route::post('/addFavProduct',[ProductController::class,'add_fav_product']);

Route::post('/removeFavProduct',[ProductController::class,'remove_fav_product']);
Route::post('/getFavouriteProducts',[ProductController::class,'get_favourite_products']);

Route::post('/sub_categories',[SubCategoryController::class,'get_all_sub_categories']);

Route::post('/sub_categories/products',[SubCategoryController::class,'get_all_products']);

Route::post('/add_customer',[CustomerController::class,'create_customer']);

Route::post('/get_customers',[CustomerController::class,'get_all_customers_per_user']);
Route::post('/search_customer',[CustomerController::class,'search_customer']);