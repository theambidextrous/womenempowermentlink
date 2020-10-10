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

Route::get('/', function (Request $request) {
    return response(['status' => 499, 'message' => 'point of no return']);
});
Route::fallback(function () {
    return response(['status'=> 499, 'message' => 'oops! Congrats! you\'ve reached point of no return']);
});
Route::prefix('/users')->group( function() {
    Route::post('/login', 'Api\UserController@signin')->name('signin');
    Route::post('/new', 'Api\UserController@signup')->name('signup');
    Route::middleware('auth:api')->group( function(){
        Route::post('/is/active', 'Api\UserController@is_active')->name('is_active');
        Route::post('/info', 'Api\UserController@info')->name('info');
    });
});
