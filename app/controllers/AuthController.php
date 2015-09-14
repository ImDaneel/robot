<?php

use Robot\Listeners\UserCreatorListener;
use Robot\Listeners\AuthenticatorListener;

class AuthController extends BaseController implements UserCreatorListener, AuthenticatorListener {

    public function authenticate()
    {
        $data = Input::only('phone', 'password');
        return App::make('Robot\Users\Authenticator')->authByPhone($this, $data);
    }

    /**
     * Actually creates the new user account
     */
    public function store()
    {
        $userData = Input::only('phone', 'password');
        return App::make('Robot\Creators\UserCreator')->create($this, $userData);
    }

    public function logout()
    {
        Auth::logout();
        return JsonView::make('success');
    }

    /**
     * ----------------------------------------
     * UserCreatorListener Delegate
     * ----------------------------------------
     */

    public function userCreated($user)
    {
        Auth::login($user, true);
        return JsonView::make('success', ['user' => $user]);
    }

    public function userValidationError($errors)
    {
        return JsonView::make('failed', ['errors' => $errors]);
    }

    /**
     * ----------------------------------------
     * AuthenticatorListener Delegate
     * ----------------------------------------
     */

    public function userNotFound()
    {
        $message = array(
            'errors' => 'Wrong username or password.',
        );
        return JsonView::make('failed', $message);
    }

    public function userFound($user)
    {
        Auth::login($user, true);

        $jsonStr = json_encode($user, true);
        $code = md5($user['phone'].Config::get('app.forum_key'));
        $srcUrl = Config::get('app.forum_url').'login?github_id='.$user['id'].'&phone='.$user['phone'].'&code='.$code;
        //$header = array('Location' => 'http://phphub.app:8000/login?github_id=1&phone=18920298765&code=693c8c6794b8c4d6d3d371be02b782dd');
        //return JsonView::make('success', ['user' => $user]);
        return View::make('auth.userfound', compact('jsonStr', 'srcUrl'));
    }

}
