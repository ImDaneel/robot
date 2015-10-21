<?php

class PushNotification extends \Eloquent
{
    protected $guarded = ['id'];

    protected $hidden = ['id', 'sign', 'created_at'];

    public $timestamps = false;
}
