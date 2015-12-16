<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

# ------------------ Route patterns---------------------
Route::pattern('id', '[0-9]+');

# ------------------ Authentication ------------------------

Route::get('login', [
    'as' => 'login',
    'uses' => 'AuthController@login',
]);

Route::post('login', [
    'as' => 'login',
    'uses' => 'AuthController@login',
]);

Route::post('register',  [
    'as' => 'register',
    'uses' => 'AuthController@register',
]);

Route::get('logout', [
    'as' => 'logout',
    'uses' => 'AuthController@logout',
]);

Route::post('initialize', [
    'as' => 'initialize',
    'uses' => 'AuthController@initialize',
]);

Route::get('send-verify-code', [
    'as' => 'send-verify-code',
    'uses' => 'AuthController@sendVerifyCode',
]);

Route::post('auth', [
    'as' => 'auth',
    'uses' => 'AuthController@authenticate',
]);

# ------------------ User ------------------------

Route::get('user', [
    'as' => 'user.show',
    'uses' => 'UserController@show',
    'before' => 'auth',
]);

Route::post('user/update', [
    'as' => 'user.update',
    'uses' => 'UserController@update',
    'before' => 'auth',
]);

Route::delete('user/{id}', [
    'as' => 'user.delete',
    'uses' => 'UserController@destroy',
    'before' => 'auth',
]);

Route::get('user/robots', [
    'as' => 'user.robots',
    'uses' => 'UserController@getRobots',
    'before' => 'auth',
]);

Route::post('user/auth-response', [
    'as' => 'user.auth-response',
    'uses' => 'UserController@authResponse',
    'before' => 'auth',
]);

Route::get('user/feedbacks', [
    'as' => 'user.feedbacks',
    'uses' => 'UserController@getFeedbacks',
    'before' => 'auth',
]);

Route::post('user/feedback/create', [
    'as' => 'user.feedback.create',
    'uses' => 'FeedbackController@store',
    'before' => 'auth',
]);

Route::post('user/feedback/upload_image', [
    'as' => 'user.feedback.upload_image',
    'uses' => 'FeedbackController@uploadImage',
    'before' => 'auth',
]);

Route::get('user/feedback/{id}', [
    'as' => 'user.feedback',
    'uses' => 'FeedbackController@show',
    'before' => 'auth',
]);

Route::post('user/evaluate', [
    'as' => 'user.evaluate',
    'uses' => 'EvaluateController@store',
    'before' => 'auth',
]);

# ------------------ Version ------------------------

Route::get('version/push-latest', [
    'as' => 'version.push-latest',
    'uses' => 'VersionController@pushLatest',
]);

# ------------------ Resource Route ------------------------

Route::resource('robot.schedule', 'RobotScheduleController');
Route::resource('robot.map', 'RobotMapController', ['only' => ['index', 'store', 'show']]);
Route::resource('robot.report', 'RobotReportController', ['only' => ['index', 'store']]);
Route::resource('robot.log', 'RobotLogController', ['only' => ['index', 'store']]);


# ------------------ Staff ------------------------

Route::get('staff/login', [
    'as' => 'staff.login',
    'uses' => 'StaffController@login',
]);

Route::post('staff/login', [
    'as' => 'staff.login',
    'uses' => 'StaffController@authenticate',
]);

Route::get('staff/logout', [
    'as' => 'staff.logout',
    'uses' => 'StaffController@logout',
]);

Route::get('staff/home', [
    'as' => 'staff.home',
    'uses' => 'StaffController@home',
    'before' => 'staff_auth',
]);

Route::get('/staff/update_avatar', [
    'as' => 'staff.update_avatar',
    'uses' => 'StaffController@updateAvatar',
    'before' => 'staff_auth',
]);

Route::post('/staff/update_avatar', [
    'as' => 'staff.update_avatar',
    'uses' => 'StaffController@uploadAvatar',
    'before' => 'staff_auth',
]);

Route::post('staff/upload_image', [
    'as' => 'staff.upload_image',
    'uses' => 'ReplyController@uploadImage',
    'before' => 'staff_auth',
]);


Route::group(['before' => 'staff_auth'], function () {
    Route::resource('feedback', 'FeedbackController', ['only' => ['index', 'show']]);
    Route::resource('reply', 'ReplyController', ['only' => ['store']]);
    Route::resource('staff', 'StaffController');
});

