<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('auth/login', 'AuthController@login');
Route::post('auth/signup', 'AuthController@signup');

Route::group(['middleware' => 'jwt.verify'], function () {
    Route::group(['prefix' => 'auth'], function(){
        Route::get('/current', 'AuthController@getCurrentUser');
        Route::post('/resetpassword',  'AuthController@sendMail');
    });
    
    Route::group(['prefix' => 'schedule'], function () {
        Route::get('/', 'ScheduleController@getAll');
    });

    Route::group(['prefix' => 'booking'], function(){
        Route::get('/my',      'BookingController@getMyBookings');
        Route::post('/book',   'BookingController@createBooking');
        Route::post('/all',    'BookingController@getAllBookings');
        Route::get('/{id}',    'BookingController@getBooking')->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'order'], function(){
        Route::put('/create',   'OrderController@createOrder');
    });

    Route::group(['prefix' => 'products'], function(){
        Route::post('/', 'ProduitController@getAllProducts');
    });

    
});

