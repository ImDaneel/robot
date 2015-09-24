<?php namespace Robot\Users;

use Robot\Listeners\AuthenticatorListener;
use Robot\Validators\UserLoginValidator;
use User;
use VerifyCode;

/**
* This class can call the following methods on the listener object:
*
* userFound($user)
* userNotFound()
*/
class Authenticator
{
    protected $userModel;
    protected $validator;

    public function __construct(User $userModel, UserLoginValidator $userLoginValidator)
    {
        $this->userModel = $userModel;
        $this->validator = $userLoginValidator;
    }

    public function authByPassword(AuthenticatorListener $listener, $data)
    {
        $this->validator->validate($data);
        $user = $this->userModel->getByPhoneAndPassword($data['phone'], $data['password']);

        if ($user) {
            return $listener->UserFound($user);
        }
        return $listener->userNotFound();
    }

    public function authByVerifyCode(AuthenticatorListener $listener, $data)
    {
        $this->validator->validate($data);
        if (! VerifyCode::verify($data['phone'], $data['verify_code'])) {
            return $listen->userValidationError('verify code error');
        }

        $user = $this->userModel->getByPhone($data['phone']);
        if ($user) {
            return $listener->UserFound($user);
        }

        // if user not found, create a new account
        //return $listener->userNotFound();
        $user = User::create(['phone'=>$data['phone']]);
        if (! $user) {
            return $listen->userValidationError($user->getErrors());
        }
        return $listenr->userCreated($user);

    }

    public function authByRobotSn(AuthenticatorListener $listener, $robot_sn)
    {
        $user = $this->userModel->getByRobotSn($robot_sn);

        if ($user) {
            return $listener->UserFound($user);
        }
        return $listener->userNotFound();
    }
}
