<?php

class Sale extends Eloquent {

    protected $table = "sales";
    protected $primaryKey = "id";

    protected $hidden = array('id');

    public static function insertSale($code, $lot, $exp, $type, $qty, $amt, $dsc, $inv){
        if ($type == "ret") {
            DB::table('inventory')  ->  where('p_code', $code)
                                    ->  where('lotNo', $lot)
                                    ->  where('expiry', $exp)
                                    ->  decrement('retail', $qty, array('updated_at' => date("Y-m-d H:i:s")));
        } else {
            DB::table('inventory')  ->  where('p_code', $code)
                                    ->  where('lotNo', $lot)
                                    ->  where('expiry', $exp)
                                    ->  decrement('packages', $qty, array('updated_at' => date("Y-m-d H:i:s")));
        }

        $saleData = array(  'p_code'        => $code,
                            'type'          => $type,
                            'qty'           => $qty,
                            'amount'        => $amt,
                            'discount'      => $dsc,
                            'branchID'      => Session::get('branchid'),
                            'datetime'      => date("Y-m-d H:i:s"),
                            'cashierName'   => Auth::user() -> name,
                        );
        $invoiceNum = '';
        if($inv != '') {
            $saleData['invoiceNumber'] = $inv;
            $invoiceNum = $inv;

            $saleid = DB::table('sales')   ->  insertGetId($saleData);
        } else {
            $saleid = DB::table('sales')   ->  insertGetId($saleData);
            $invoiceNum = date("Ymd") . "-" . sprintf("%05d", $saleid);

            DB::table('sales')
                    ->where('id', $saleid)
                    ->update(array('invoiceNumber' => $invoiceNum));
        }

        return $invoiceNum;
    }

    public static function getSaleByDate($from, $to, $branch = 'All') {
        $sale = DB::table('sales');
        $sale           -> select((DB::raw("type, SUM(qty) as qty, SUM(discount) as discount,
                                                        datetime, SUM(amount) as amount")))
                        -> addSelect('sales.p_code');

        if($from != $to){
            $sale       -> where('datetime', '>=', $from)
                        -> where('datetime', '<=', $to);
        } else {
            $sale       -> where('datetime', 'LIKE', $from . '%');
        }

        if($branch != 'All') {
            $branchid = Session::get('branchid');
            $sale       -> where('branchID', '=', $branchid);
        }
        
        $sale           -> where('branchID', '=', Session::get('branchid'))
                        -> groupBy('sales.p_code')
                        -> groupBy('sales.type')
                        -> orderBy('datetime', 'asc');

        return $sale    -> get();
    }

    public static function getTotalSaleOnDate($date) {
        $date = date("Y-m-d", strtotime($date));
        return DB::table('sales')   -> select((DB::raw("SUM(amount) as amount")))
                                    -> where('type', '=', 'ret')
                                    -> where('datetime', 'LIKE' , $date . "%")
                                    -> groupBy('type')
                                    -> get();
    }

    public static function topSelling() {
        return DB::table('sales')   -> select((DB::raw("SUM(amount) as amount")))
                                    -> where('type', '=', 'ret')
                                    -> groupBy('type')
                                    -> take(5)
                                    -> remember(60)
                                    -> get();
    }
}