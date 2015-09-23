<?php

class VerifyCode extends \Eloquent
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public static function verify($phone, $code)
    {
        $record = static::where('phone', '=', $phone)->orderBy('created_at', 'desc')->first();

        if (! $record || $record['code'] != $code) {
            return false;
        }

        return true;
    }
}
