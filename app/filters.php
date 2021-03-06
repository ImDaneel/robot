<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
    //
    if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        $statusCode = 204;

        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Allow'                            => 'POST,GET,OPTIONS,PUT,DELETE',
            'Access-Control-Allow-Headers'     => 'Content-Type,X-Auth-Token,Orgin',
            'Access-Control-Allow-Credentials' => 'true'
        ];

        return Response::make(null, $statusCode, $headers);
    }
});


App::after(function($request, $response)
{
    if ($response instanceof Symfony\Component\HttpFoundation\BinaryFileResponse) {
        return $response;
    }

    $response->header('Access-Control-Allow-Origin', '*')
             ->header('Access-Control-Allow-Methods', 'POST,GET,OPTIONS,PUT,DELETE')
             ->header('Access-Control-Allow-Headers', 'Content-Type,X-Auth-Token,Orgin')
             ->header('Access-Control-Allow-Credentials', 'true');

    if (Config::get('app.debug')) {
        $fp = fopen('/home/vagrant/robot/app/storage/logs/log', "a+");
        $out = date("Y-m-d H:i:s", time()) . ": request is accepted\n";
        $out .= $request . "\n";
        $out .= $response . "\n";
        fputs($fp, $out);
        fclose($fp);
    }

    return $response;
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
    if (Auth::guest()) {
        return Response::make('Unauthorized', 401);
    }
});

Route::filter('staff_auth', function()
{
    if (StaffAuth::guest()) {
        if (Request::ajax() || Request::wantsJson()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('staff/login');
        }
    }
});


Route::filter('auth.basic', function()
{
    return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
    if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
    if (Session::token() !== Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});
