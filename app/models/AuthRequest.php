<?php

class AuthRequest extends \Eloquent
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public static function validate(array $data)
    {
        return ! empty(static::where($data)->first());
    }
}
