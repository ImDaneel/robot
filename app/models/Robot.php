<?php

class Robot extends \Eloquent
{

    // Enable soft delete
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];

    protected $table = 'robots';

    protected $guarded = ['id'];

    protected $hidden = [
        'id',
        'admin_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot',
    ];

    public function users()
    {
        return $this->belongsToMany('\User', 'robot_user');
    }

    public function adminUser()
    {
        return $this->belongsTo('\User', 'admin_id');
    }

    public function getAdminSign()
    {
        $user = $this->adminUser()->get();
        if (! empty($user->phone)) {
            return $user->phone;
        }
        return $this->sn;
    }

    public static function findBySn($sn)
    {
        return static::where('sn', '=', $sn)->firstOrFail();
    }

    public function addUser($userId)
    {
        if (! $this->users()->find($userId)) {
            $this->users()->attach($userId);
        }
    }

    public function schedules()
    {
        return $this->hasMany('Schedule');
    }

    public function maps()
    {
        return $this->hasMany('Map');
    }

    public function cleanReports()
    {
        return $this->hasMany('CleanReport');
    }
}
