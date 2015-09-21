<?php namespace Robot\Creators;

use Robot\Validators\InitializeValidator;
use Robot\Listeners\UserCreatorListener;
use Illuminate\Support\Facades\DB;
use User;
use Robot;
use Auth;

/**
* This class can call the following methods on the observer object:
*
* userValidationError($errors)
* userCreated($user)
*/
class InitializeCreator
{
    protected $validator;

    public function __construct(InitializeValidator $initializeValidator)
    {
        $this->validator = $initializeValidator;
    }

    public function createAndBind(UserCreatorListener $observer, $data)
    {
        // Validation
        $this->validator->validate($data);

        $user = Auth::user();
        $robot = Robot::where('sn', '=', $data['robot_sn'])->first();

        if (! $robot && ! $user) {
            return $this->createUserAndRobot($observer, $data);
        } else if ($robot && ! $user) {
            return $this->retrieveUser($observer, $data, $robot);
        } else if (! $robot && $user) {
            return $this->createRobotAndSetAdmin($observer, $data, $user);
        }

        // else, if $robot && $user
        if (! $user->is_admin) {
            return $this->bindAndSetAdmin($observer, $robot, $user);
        } else if ($robot->admin_id != $user->id) { // not bound
            return $this->bind($observer, $robot, $user);
        }

        // else, do nothing
        return $observer->userCreated($user);
    }

    private function createUserAndRobot($observer, $data)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'is_admin' => true,
                'app_type' => $data['app_type']
            ]);

            $robot = Robot::create([
                'sn' => $data['robot_sn'],
                'admin_id' => $user->id
            ]);

            $robot->users()->sync([$user->id]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $observer->userValidationError($e->getMessage());
        }

        return $observer->userCreated($user);
    }

    private function retrieveUser($observer, $data, $robot)
    {
        if ($user = $robot->adminUser()->get()) {
            $observer->userCreated($user);
        }

        DB::beginTransaction();
        try {
            $user = new User;
            $user->is_admin = true;
            $user->app_type = $data['app_type'];
            $user->save();

            $robot->admin_id = $user->id;
            $robot->save();
            $robot->users()->sync([$user->id]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $observer->userValidationError($e->getMessage());
        }

        return $observer->userCreated($user);
    }

    private function createRobotAndSetAdmin($observer, $data, $user)
    {
        DB::beginTransaction();
        try {
            $user->is_admin = true;
            $user->save();

            $robot = Robot::create([
                'sn' => $data['robot_sn'],
                'admin_id' => $user->id
            ]);

            $robot->users()->sync([$user->id]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $observer->userValidationError($e->getMessage());
        }

        return $observer->userCreated($user);
    }

    private function bindAndSetAdmin($observer, $robot, $user)
    {
        DB::beginTransaction();
        try {
            $robot->users()->sync([$user->id]);
            $user->is_admin = true;
            $user->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $observer->userValidationError($e->getMessage());
        }

        return $observer->userCreated($user);
    }

    private function bind($observer, $robot, $user)
    {
        DB::beginTransaction();
        try {
            $robot->users()->sync([$user->id]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $observer->userValidationError($e->getMessage());
        }

        return $observer->userCreated($user);
    }

}
