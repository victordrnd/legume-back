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
    Route::get('/auth/current', 'AuthController@getCurrentUser');
    
    Route::group(['prefix' => 'schedule'], function () {
        Route::get('/', 'ScheduleController@getAll');
    });

    Route::group(['prefix' => 'booking'], function(){
        Route::get('/my',   'BookingController@getMyBookings');
        Route::post('/book',   'BookingController@createBooking');
    });

    Route::group(['prefix' => 'products'], function(){
        Route::post('/', 'ProduitController@getAllProducts');
    });
});

