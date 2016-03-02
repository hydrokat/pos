<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Transfers extends Eloquent {

    protected $table = "transfers";
    protected $primaryKey = "id";

    protected $hidden = array('id');
    protected $guarded = array('id');

    public $timestamps = false;
}