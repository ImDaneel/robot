<?php

class CleanReport extends \Eloquent
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

    public function getAreaAttribute($value)
    {
        settype($value, 'float');
        return $value;
    }

    public function getPercentAttribute($value)
    {
        if ($value) {
            settype($value, 'float');
        }
        return $value;
    }
}
