<?php

class Client extends \Eloquent {

    public $timestamps = false;

    protected $connection = 'mysql';

    protected $fillable = [
        'id',
        'type',
        'external_addr',
        'internal_addr',
    ];

}
