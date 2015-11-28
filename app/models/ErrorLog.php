<?php

class ErrorLog extends \Eloquent
{
    protected $guarded = ['id'];

    public $timestamps = false;

    protected $appends = ['robot_sn'];

    protected $hidden = ['id', 'robot_id'];

    public function robot()
    {
        return $this->belongsTo('Robot');
    }

    public function getRobotSnAttribute()
    {
        return $this->robot()->first()->sn;
    }
}
