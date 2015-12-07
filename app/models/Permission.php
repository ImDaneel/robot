<?php

use Zizaco\Entrust\EntrustPermission;

class Permission extends \Eloquent
{
    protected $guarded = ['id'];

    public function roles()
    {
        return $this->belongsToMany('Role');
    }

}
