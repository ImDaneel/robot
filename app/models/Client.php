<?php

class Client extends Eloquent {

    // Enable soft delete
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];

    protected $guarded = [];
}
