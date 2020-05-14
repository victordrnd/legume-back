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

Route::group(['prefix' => 'products'], function(){
    Route::post('/','ProduitController@getAllProducts');
});

Route::group(['middleware' => 'jwt.verify'], function () {
    Route::get('/auth/current', 'AuthController@getCurrentUser');
    Route::put('/auth/user/update', 'AuthController@updateCurrentUser');
    
    Route::group(['prefix' => 'schedule'], function () {
        Route::get('/', 'ScheduleController@getAll');
    });

    Route::group(['prefix' => 'booking'], function(){
        Route::get('/all',           'BookingController@getAllBookings')->middleware('can:viewAny,App\Booking');
        Route::get('/my',            'BookingController@getMyBookings');
        Route::get('/{booking}',     'BookingController@getBooking')->where('id', '[0-9]+')->middleware('can:view,booking');
        Route::get('/order/{booking:order_id}', 'BookingController@getBookingByOrderId')->where('booking:order_id', '[0-9]+')->middleware('can:view,booking');
        Route::post('/book',         'BookingController@createBooking')->middleware('can:create,App\Booking');
        Route::delete('/{booking}',  'BookingController@cancelBooking')->where('id', '[0-9]+')->middleware('can:delete,booking');
    });

    Route::group(['prefix' => 'order'], function(){
        Route::post('/create',   'OrderController@createOrder');
        Route::put('/prepare',   'OrderController@prepareOrder')->middleware('can:operator');
        Route::put('/edit',      'OrderController@editQuantity')->middleware('can:operator');
    });

    Route::group(['prefix' => 'payment'], function(){
        Route::get('/create',   'PaymentController@createPaymentIntent');
        Route::post('/confirm', 'PaymentController@confirmPayment');
        Route::post('/charge',  'PaymentController@charge');
    });

    Route::group(['prefix' => 'import', 'middleware' => 'can:administrator'], function(){
        Route::get('/',     'ImportController@getAll');
        Route::post('/new', 'ImportController@import');
        Route::delete('/',  'ImportController@deleteImport');
    });


    Route::group(['prefix' => 'user', 'middleware' => 'can:administrator'], function(){
        Route::get('/', 'UserController@filter');
        Route::get('/roles',    'UserController@getAllRoles');
        Route::put('/role',     'UserController@updateRole');
    });

    
});

