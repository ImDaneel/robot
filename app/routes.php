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
    'uses' => 'AuthController@authenticate',
]);

Route::post('login', [
    'as' => 'login',
    'uses' => 'AuthController@authenticate',
]);

Route::post('signup',  [
    'as' => 'signup',
    'uses' => 'AuthController@store',
]);

Route::get('logout', [
    'as' => 'logout',
    'uses' => 'AuthController@logout',
]);

