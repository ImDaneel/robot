<?php

class Version extends \Eloquent
{
    protected $fillable = [];

    public static function getLatest($type)
    {
        return static::where(['type'=>strtolower($type)])->orderBy('updated_at', 'desc')->firstOrFail();
    }
}
