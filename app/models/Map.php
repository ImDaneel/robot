<?php

class Map extends \Eloquent
{
    protected $guarded = ['id'];

    public $timestamps = false;

    protected $appends = ['robot_sn', 'map_url'];

    protected $hidden = ['robot_id', 'file_path'];

    public function robot()
    {
        return $this->belongsTo('Robot');
    }

    public function getRobotSnAttribute()
    {
        return $this->robot()->first()->sn;
    }

    public function getOriginAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setOriginAttribute($value)
    {
        $this->attributes['origin'] = json_encode($value, JSON_NUMERIC_CHECK);
    }

    public function getMapUrlAttribute()
    {
        return Config::get('app.url') . 'robot/' . $this->robot_sn . '/map/' . $this->id;
    }
}
