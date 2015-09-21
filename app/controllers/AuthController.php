<?php

use Robot\Listeners\UserCreatorListener;
use Robot\Listeners\AuthenticatorListener;

class AuthController extends BaseController implements UserCreatorListener, AuthenticatorListener
{

    public function authenticate()
    {
        if (Input::has('robot_sn')) {
            return App::make('Robot\Users\Authenticator')->authByRobotSn($this, Input::only('robot_sn'));
        }
        $data = Input::only('phone', 'password');
        return App::make('Robot\Users\Authenticator')->authByPhone($this, $data);
    }

    /**
     * Actually creates the new user account
     */
    public function register()
    {
        $userData = Input::only('phone', 'password');
        return App::make('Robot\Creators\UserCreator')->create($this, $userData);
    }

    public function logout()
    {
        Auth::logout();
        return $this->logoutView();
    }

    /**
     * Creates the new user account and bind a bobot
     */
    public function initialize()
    {
        $data = Input::only('app_type', 'robot_sn');
        return App::make('Robot\Creators\InitializeCreator')->createAndBind($this, $data);
    }

    /**
     * ----------------------------------------
     * UserCreatorListener Delegate
     * ----------------------------------------
     */

    public function userCreated($user)
    {
        Auth::login($user, true);
        return $this->userView($user);
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
        return JsonView::make('failed', ['errors' => 'Wrong username or password.']);
    }

    public function userFound($user)
    {
        Auth::login($user, true);
        return $this->userView($user);
    }

    private function userView($user)
    {
        $forumName = $user->getForumName();
        $code = md5($forumName.Config::get('app.forum_key'));
        $srcUrl = Config::get('app.forum_url').'login?id='.$user['id'].'&name='.$forumName.'&code='.$code;

        if (Request::wantsJson()) {
            $script = ['type' => 'text/javascript', 'src'  => $srcUrl];
            return JsonView::make('success', ['user' => $user, 'script' => $script]);
        }

        $resp = [
            'code' => 'success',
            'message' => ['user' => $user],
            '_token' => csrf_token(),
        ];
        $jsonStr = json_encode($resp, true);

        return View::make('auth.userfound', compact('jsonStr', 'srcUrl'));
    }

    private function logoutView()
    {
        $srcUrl = Config::get('app.forum_url').'logout';

        if (Request::wantsJson()) {
            $script = ['type' => 'text/javascript', 'src'  => $srcUrl];
            return JsonView::make('success', ['script' => $script]);
        }

        $resp = [
            'code' => 'success',
            '_token' => csrf_token(),
        ];
        $jsonStr = json_encode($resp, true);
        return View::make('auth.logout', compact('jsonStr', 'srcUrl'));
    }
}
