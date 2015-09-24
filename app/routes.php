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

# ------------------ User stuff ------------------------

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

# ------------------ Resource Route ------------------------

//Route::resource('user', 'UserController', ['only' => ['show', 'update', 'destroy']]);

