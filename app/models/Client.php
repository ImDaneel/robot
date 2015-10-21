<?php

class Client extends Eloquent
{
    protected $guarded = ['id'];

    protected $hidden = ['id'];

    public $timestamps = false;
}
