<?php

class Schedule extends \Eloquent
{
    protected $guarded = ['id'];

    public $timestamps = false;

    protected $appends = ['robot_sn'];

    protected $hidden = ['robot_id'];

    public function robot()
    {
        return $this->belongsTo('Robot');
    }

    public function getRobotSnAttribute()
    {
        return $this->robot()->first()->sn;
    }

    public function getRepeatAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setRepeatAttribute($value)
    {
        $this->attributes['repeat'] = json_encode($value, JSON_NUMERIC_CHECK);
    }
}
