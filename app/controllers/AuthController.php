<?php

use Robot\Listeners\UserCreatorListener;
use Robot\Listeners\AuthenticatorListener;
use Robot\Utils\PushService;

class AuthController extends BaseController implements UserCreatorListener, AuthenticatorListener
{

    public function login()
    {
        if (Input::has('robot_sn')) {
            return App::make('Robot\Users\Authenticator')->authByRobotSn($this, Input::only('robot_sn'));
        }
        if (Input::has('password')) {
            $data = Input::only('phone', 'password');
            return App::make('Robot\Users\Authenticator')->authByPassword($this, $data);
        } else {
            $data = Input::only('phone', 'verify_code');
            return App::make('Robot\Users\Authenticator')->authByVerifyCode($this, $data);
        }
    }

    /**
     * Actually creates the new user account
     */
    public function register()
    {
        $userData = Input::only('app_type', 'phone', 'robot_sn', 'token');
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

    public function sendVerifyCode()
    {
        $phone = Input::get('phone');
        $rand = rand(1000, 9999);
        $ret = Robot\Utils\Sms::send($phone, $rand, 5);

        if ($ret['code'] == 'success') {
            VerifyCode::create([
                'phone' => $phone,
                'code' => $rand,
                'created_at' => time(),
            ]);
        }

        return JsonView::make($ret['code'], isset($ret['error'])?['errors'=>$ret['error']]:[]);
    }

    public function authenticate()
    {
        $data = Input::all();
        App::make('Robot\Validators\AuthValidator')->validate($data);
        if (! VerifyCode::verify($data['phone'], $data['verify_code'])) {
            return JsonView::make('error', ['errors'=>'verify code error']);
        }

        $sign = Robot::findBySn($data['robot_sn'])->getAdminSign();
        $content = [
            'phone' => $data['phone'],
            'robot_sn' => $data['robot_sn'],
            'token' => md5($data['phone'] . $data['robot_sn'] . date('YmdHis', time())),
        ];

        if (! PushService::push('auth', $sign, $content)) {
            // if not success, save it
            //return JsonView::make('failed', ['errors'=>'push message error']);
        }

        $content['created_at'] = time();
        AuthRequest::create($content);

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
        return $this->userView($user);
    }

    public function userValidationError($errors)
    {
        return JsonView::make('error', ['errors' => $errors]);
    }

    /**
     * ----------------------------------------
     * AuthenticatorListener Delegate
     * ----------------------------------------
     */

    public function userNotFound()
    {
        return JsonView::make('error', ['errors' => 'Wrong username or password.']);
    }

    public function userFound($user)
    {
        Auth::login($user, true);
        return $this->userView($user);
    }

    private function userView($user)
    {
        $forumName = $user->getName();
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
