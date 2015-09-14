<?php namespace Robot\Creators;

use Robot\Validators\UserSignupValidator;
use Robot\Listeners\UserCreatorListener;
use User;

/**
* This class can call the following methods on the observer object:
*
* userValidationError($errors)
* userCreated($user)
*/
class UserCreator
{
    protected $userModel;
    protected $validator;

    public function __construct(User $userModel, UserSignupValidator $signupValidator)
    {
        $this->userModel = $userModel;
        $this->validator = $signupValidator;
    }

    public function create(UserCreatorListener $observer, $data)
    {
        // Validation
        $this->validator->validate($data);
        return $this->createValidUserRecord($observer, $data);
    }

    private function createValidUserRecord($observer, $data)
    {
        $user = User::create($data);
        if (! $user) {
            return $observer->userValidationError($user->getErrors());
        }
        return $observer->userCreated($user);
    }
}
