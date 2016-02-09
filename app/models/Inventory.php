<?php

class Inventory extends Eloquent {
    protected $table = "inventory";
    protected $primaryKey = "id";

    protected $hidden = array('id');

    public static function getInventory() {
        return DB::table('inventory') -> join('items', function($j){
                                            $j -> on('items.p_code', '=', 'inventory.p_code')
                                               -> on('items.lotNo', '=', 'inventory.lotNo')
                                               -> on('items.expiry', '=', 'inventory.expiry');
                                          })
                                      -> whereNull('deleted_at')
                                      /*-> where('retail', '>', '0')
                                      -> orWHere('packages', '>', '0')*/
                                      -> orderBy('inventory.updated_at', 'DESC')
                                      -> get();
    }

    public static function getQuantity($code) {
        return DB::table('inventory') -> select(DB::raw('SUM(packages) as packages, SUM(retail) as retail'))
                                      //-> join('items', 'items.p_code', '=', 'inventory.p_code')
                                      -> where('p_code', '=', $code)
                                      -> groupBy('p_code')
                                      //-> whereNull('deleted_at')
                                      -> get();
    }

    public static function getInventoryLt() {
        return DB::table('inventory')   -> select('inventory.p_code', 'inventory.lotNo', 'inventory.expiry', 'packages', 'retail', 'inventory.updated_at', 'name', 'inventory_threshold', 'inventory.expiry', 'inventory.acquisition_price')
                                        -> join('items', function($j){
                                            $j -> on('items.p_code', '=', 'inventory.p_code')
                                               -> on('items.lotNo', '=', 'inventory.lotNo')
                                               -> on('items.expiry', '=', 'inventory.expiry');
                                          })
                                        -> whereNull('deleted_at')
                                        -> orderBy('inventory.updated_at', 'DESC')
                                        -> get();
    }
}