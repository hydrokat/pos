<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Item extends Eloquent {

    use SoftDeletingTrait;

    protected $softDelete = true; 

    protected $table = "items";
    protected $primaryKey = "id";

    protected $dates = ['deleted_at'];

    protected $hidden = array('id');
    protected $guarded = array('id');

    public static function insertItem($code, $name, $size, $cat, $exp, $inv_thresh, $priceR, $priceP){
        return DB::table('items')   ->  insertGetId(
                                                array(  'p_code'    => $code,
                                                        'name'      => $name,
                                                        'size'       => $size,
                                                        'category'    => $cat,
                                                        'expiry'  => $exp,
                                                        'inventory_threshold'  => $inv_thresh,
                                                        'price_retail'  => $priceR,
                                                        'price_package'  => $priceP,
                                                    )
                                            );
    }
}