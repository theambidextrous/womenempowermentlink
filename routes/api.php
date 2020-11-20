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
    Route::post('/login', 'Api\ApiController@signin');
    Route::post('/new', 'Api\ApiController@signup');
    Route::post('/request/reset/{email}', 'Api\ApiController@reqreset');
    Route::post('/verify/{code}/reset/{email}', 'Api\ApiController@verifyreset');
    Route::post('/finish/reset', 'Api\ApiController@finishreset');
    Route::middleware('auth:api')->group( function(){
        Route::post('/is/active', 'Api\ApiController@is_active');
        Route::post('/info', 'Api\ApiController@info');
    });
});
Route::prefix('/account')->group( function() {
    Route::middleware('auth:api')->group( function(){
        Route::post('/update/profile', 'Api\ApiController@update_profile');
        Route::post('/update/device/{ptoken}', 'Api\ApiController@d_token');
        Route::post('/update/profile/pic', 'Api\ApiController@update_p_pic');
        Route::post('/test/push', 'Api\ApiController@test_push');
    });
});
Route::prefix('/courses')->group( function() {
    Route::middleware('auth:api')->group( function(){
        Route::get('/get/all', 'Api\ApiController@get_courses');
        Route::post('/drop/{id}', 'Api\ApiController@drop_course');
        Route::post('/enroll/{id}', 'Api\ApiController@enroll_course');
    });
});
Route::prefix('/units')->group( function() {
    Route::middleware('auth:api')->group( function(){
        Route::get('/get/all', 'Api\ApiController@get_units');
    });
});
Route::prefix('/lessons')->group( function() {
    Route::middleware('auth:api')->group( function(){
        Route::get('/get/all/{id}', 'Api\ApiController@get_lessons');
        Route::get('/stream/{file}', 'Api\ApiController@stream');
    });
});
Route::prefix('/exams')->group( function() {
    Route::middleware('auth:api')->group( function(){
        Route::get('/get/all/{id}', 'Api\ApiController@get_exam');
        Route::get('/questions/{exam}', 'Api\ApiController@get_exam_q');
        Route::post('/progress/mark', 'Api\ApiController@mark_exam');
        Route::post('/progress/finish', 'Api\ApiController@end_exam_finish');
    });
});
Route::prefix('/surveys')->group( function() {
    Route::middleware('auth:api')->group( function(){
        Route::get('/get/all/{id}', 'Api\ApiController@get_survey');
        Route::post('/progress/mark', 'Api\ApiController@mark_survey');
    });
});
Route::prefix('/assignments')->group( function() {
    Route::middleware('auth:api')->group( function(){
        Route::get('/get/all/{id}', 'Api\ApiController@get_assign');
        Route::post('/submit', 'Api\ApiController@submit_assign');
    });
});
Route::prefix('/performance')->group( function() {
    Route::middleware('auth:api')->group( function(){
        Route::get('/get/all/{id}', 'Api\ApiController@get_perform');
    });
});
Route::prefix('/forums')->group( function() {
    Route::middleware('auth:api')->group( function(){
        Route::get('/get/all/{id}', 'Api\ApiController@get_forums');
        Route::get('/reply/get/all/{id}', 'Api\ApiController@get_forum_reply');
        Route::post('/reply', 'Api\ApiController@forum_reply');
        Route::post('/post/new', 'Api\ApiController@forum_post');
    });
});