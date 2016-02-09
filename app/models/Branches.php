<?php

class Branches extends Eloquent {

    protected $table = "branches";
    //protected $primaryKey = array('p_code', 'branchID', 'date');

    protected $hidden = array('id');
    public $timestamps = false;
}