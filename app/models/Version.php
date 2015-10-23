<?php

class Version extends \Eloquent
{
    protected $guarded = ['id'];

    public static function getLatest($type)
    {
        return static::where(['type'=>strtolower($type)])->orderBy('updated_at', 'desc')->firstOrFail();
    }
}
