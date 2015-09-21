<?php namespace Robot\Users;

use Robot\Listeners\AuthenticatorListener;
use Robot\Validators\UserLoginValidator;
use User;

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

    public function authByPhone(AuthenticatorListener $listener, $data)
    {
        $this->validator->validate($data);
        $user = $this->userModel->getByPhoneAndPassword($data['phone'], $data['password']);

        if ($user) {
            return $listener->UserFound($user);
        }
        return $listener->userNotFound();
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
