<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class VerifyCode extends \Eloquent
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public static function verify($phone, $code)
    {
        $record = static::where('phone', '=', $phone)->orderBy('created_at', 'desc');

        try {
            if ($record->firstOrFail()->code != $code) {
                return false;
            }
        } catch (ModelNotFoundException $e) {
            return false;
        }

        $record->delete();
        return true;
    }
}
