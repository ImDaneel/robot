<?php

class Role extends \Eloquent
{
    protected $guarded = ['id'];

    public function staffs()
    {
        return $this->belongsToMany('Staff', 'assigned_roles');
    }

    public function perms()
    {
        return $this->belongsToMany('Permission');
    }
}
