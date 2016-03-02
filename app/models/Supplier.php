<?php

class Supplier extends Eloquent {

    protected $table = "suppliers";
    //protected $primaryKey = array('p_code', 'branchID', 'date');

    protected $hidden = array('id');
    public $timestamps = false;
}