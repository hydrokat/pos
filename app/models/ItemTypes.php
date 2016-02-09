<?php

class ItemTypes extends Eloquent {
    protected $table = "itemTypes";
    protected $primaryKey = "id";

    protected $hidden = array('id');
}