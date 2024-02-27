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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



// ----------Activity Request------------------------
// ###################################################


Route::post('/login', '\App\Http\Controllers\ApirequestController@login')->name('login');
Route::get('/instatus/{id}', '\App\Http\Controllers\ApirequestController@checkin_status')->name('instatus');
Route::post('/checkin', '\App\Http\Controllers\ApirequestController@check_in')->name('checkin');
Route::get('/check/{id}', '\App\Http\Controllers\ApirequestController@index')->name('check');
Route::get('/checkAttendance/{id}', '\App\Http\Controllers\ApirequestController@checkAttendance')->name('checkAttendance');
Route::post('/updateresponse', '\App\Http\Controllers\ApirequestController@update_response')->name('updateresponse');
Route::post('/clockout', '\App\Http\Controllers\ApirequestController@clockout')->name('clockout');
Route::post('/clockin', '\App\Http\Controllers\ApirequestController@clockin')->name('clockin');
Route::post('/officialclockin', '\App\Http\Controllers\ApirequestController@official_clock_in')->name('officialclockin');
Route::post('/checkout', '\App\Http\Controllers\ApirequestController@check_out')->name('checkout');




// ------------Screen Short Request---------------------
// #####################################################

Route::post('/screenshort', '\App\Http\Controllers\ApirequestController@screenShort')->name('screenshort');



