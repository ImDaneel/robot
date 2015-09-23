<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class User extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    // Enable soft delete
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    protected $guarded = ['id'];

    public function robots()
    {
        return $this->belongsToMany('\Robot', 'robot_user');
    }

    public function getByPhoneAndPassword($phone, $password)
    {
        return $this->where('phone', '=', $phone)->where('password', '=', $password)->first();
    }

    public function getByPhoneAndVerifyCode($phone, $verifyCode)
    {
        if (! VerifyCode::verify($phone, $verifyCode)) {
            return null;
        }
        return $this->where('phone', '=', $phone)->first();
    }

    public function getByRobotSn($robot_sn)
    {
        try {
            $user = Robot::where('sn', '=', $robot_sn)->firstOrFail()->adminUser()->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }

        if ($user && $user->is_admin) {
            return $user;
        }
        return null;
    }

    public function getForumName()
    {
        if (! empty($this->phone)) {
            return $this->phone;
        }

        $robots = $this->robots()->get();
        foreach ($robots as $robot) {
            if ($robot->adminUser()->first()->id == $this->id) {
                return $robot->sn;
            }
        }

        throw new ModelNotFoundException('robot sn does not exist');
    }
}
