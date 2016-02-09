<?php

class EndingInventory extends Eloquent {

    protected $table = "endingInv";
    //protected $primaryKey = array('p_code', 'branchID', 'date');

    //protected $hidden = array('id');
    public $timestamps = false;

    public static function getEnding($from, $to) {
        $ending = DB::table('endingInv')   -> join('items', 'items.p_code', '=', 'endingInv.p_code')
                                           -> orderBy('items.name', 'ASC');

        if($from != $to){
            $ending -> where('date', '>=', $from)
                    -> where('date', '<=', $to);
        } else {
            $ending -> where('date', 'LIKE', $from . '%');
        }

        return $ending -> get();
    }
}