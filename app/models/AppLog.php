<?php

class AppLog extends Eloquent {

    protected $table = "logs";
    protected $primaryKey = "id";

    protected $hidden = array('id');

    public static function insertLog($un, $action){
        return DB::table('logs')   ->  insertGetId(
                                                array(  'username'      => $un,
                                                        'action'        => $action,
                                                        'datetime'      => date("Y-m-d H:i:s"),
                                                        'branch'        => Session::get('branch', 'N/A')
                                                    )
                                            );
    }

    public static function getLogByDate($from, $to) {
        $logs = DB::table('logs');

        if($from != $to){
            $logs       -> where('datetime', '>=', $from)
                        -> where('datetime', '<=', $to);
        } else {
            $logs       -> where('datetime', 'LIKE', $from . '%');
        }
        
        $logs           -> orderBy('datetime', 'DESC');
        return $logs    -> get();
    }
}