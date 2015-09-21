<?php

class Robot extends \Eloquent {

    // Enable soft delete
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];

    protected $table = 'robots';

    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany('\User', 'robot_user');
    }

    public function adminUser()
    {
        return $this->belongsTo('\User', 'admin_id');
    }
}
