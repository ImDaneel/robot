<?php namespace Robot\Creators;

use Robot\Listeners\UserCreatorListener;
use User;
use AuthRequest;
use Robot;

/**
* This class can call the following methods on the observer object:
*
* userValidationError($errors)
* userCreated($user)
*/
class UserCreator
{
    public function create(UserCreatorListener $observer, $data)
    {
        // Validation
        $validData = $data;
        unset($validData['app_type']);
        $validData['reply'] = 'accept';
        if (! AuthRequest::validate($validData)) {
            return $observer->userValidationError('authentication data error');
        }

        $user = User::where(['phone'=>$data['phone']])->first();
        if ($user) {
            return $observer->userValidationError('this account is already registered');
        }

        return $this->createValidUserRecord($observer, $data);
    }

    private function createValidUserRecord($observer, $data)
    {
        $user = User::create([
            'app_type' => $data['app_type'],
            'phone' => $data['phone'],
        ]);

        if (! $user) {
            return $observer->userValidationError($user->getErrors());
        }

        Robot::findBySn($data['robot_sn'])->addUser($user->id);

        return $observer->userCreated($user);
    }
}
