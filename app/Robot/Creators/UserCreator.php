<?php namespace Robot\Creators;

use Robot\Validators\RegisterValidator;
use Robot\Listeners\UserCreatorListener;
use User;
use Auth;

/**
* This class can call the following methods on the observer object:
*
* userValidationError($errors)
* userCreated($user)
*/
class UserCreator
{
    protected $validator;

    public function __construct(RegisterValidator $registerValidator)
    {
        $this->validator = $registerValidator;
    }

    public function create(UserCreatorListener $observer, $data)
    {
        // Validation
        $this->validator->validate($data);

        $user = Auth::user();
        if ($user) {
            $this->updateUserRecord($observer, $data);
        }

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

    private function updateUserRecord($observer, $data)
    {
        if (! empty($user->phone)) {
                return $observer->userValidationError('this account is already registered');
            }

            $user->update($data);
            return $observer->userCreated($user);
    }
}
