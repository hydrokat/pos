<?php

class ReportsController extends BaseController {
    public $restful = true;

    public function getSalesReport() {

        $branch = Input::get('branch') == 'All' ? 'All' : Input::get('branch');
        $from = Input::has('from') ? Input::get('from') : date("Y-m-d");
        $to   = Input::has('to') ? new DateTime(Input::get('to')) : new DateTime(date("Y-m-d"));

        $to = date_time_set($to, 23, 59 ,59);
        $to = $to -> format('Y-m-d H:i:s');

        $sales = Sale::getSaleByDate($from, $to, $branch);

        $tDsc = 0;
        $tAmt = 0;

        foreach ($sales as $s) {
            $tDsc += $s -> discount;
            $tAmt += $s -> amount;
        }

        $settings = $this -> fetchSettings();

        return View::make('pages.reports.saleReport') -> with('sales', $sales)
                                              -> with('dStart', $from)
                                              -> with('dEnd', $to)
                                              -> with('tDsc', $tDsc)
                                              -> with('tAmt', $tAmt)
                                              -> with('branches', $settings['branches']);
    }

    public function getInventoryReport(){
        $inv = Inventory::getInventoryLt();
        //$branch = Branches::where('name', '=', Session::get('branch')) -> first();

        foreach ($inv as $i) {
            $ending = EndingInventory::where('p_code', '=', $i -> p_code) 
                                        -> where('lotNo', '=', $i -> lotNo)
                                        -> where('expiry', '=', $i -> expiry)
                                        -> where('branchID', '=', Session::get('branchid'))
                                        -> where('date', '=', date("Y-m-d"))
                                        -> first();

            if($ending === null) {
                //item is new
                $ending = new EndingInventory;
            }

            $ending -> p_code   = $i -> p_code;
            $ending -> lotNo    = $i -> lotNo;
            $ending -> expiry   = $i -> expiry;
            $ending -> branchID = Session::get('branchid');
            $ending -> date     = date("Y-m-d");
            $ending -> retail   = $i -> retail;
            $ending -> packages  = $i -> packages;            

            $ending -> save();
        }

            $e = EndingInventory::whereRaw('expiry IS NULL') -> delete();

        return View::make('pages.reports.inventory') -> with('inv', $inv);
    }

    public function getEndingInventory() {
        $from = Input::has('from') ? Input::get('from') : date("Y-m-d");
        $to   = Input::has('to') ? new DateTime(Input::get('to')) : new DateTime(date("Y-m-d"));

        $to = date_time_set($to, 23, 59 ,59);
        $to = $to -> format('Y-m-d');

        $inv = EndingInventory::getEnding($from, $to);

        return View::make('pages.reports.inventory') -> with('inv', $inv)
                                             -> with('dStart', $from)
                                             -> with('dEnd', $to);
    }

    public function getTransfers() {
        $from = Input::has('from') ? Input::get('from') : date("Y-m-d");
        $to   = Input::has('to') ? new DateTime(Input::get('to')) : new DateTime(date("Y-m-d"));

        $to = date_time_set($to, 23, 59 ,59);
        $to = $to -> format('Y-m-d H:i:s');

        $branches = Branches::all();

        if($from != $to){
            $transfers = Transfers::where('datetime', '>=', $from)
                                    -> where('datetime', '<=', $to)
                                    -> orderBy('datetime', 'DESC');
        } else {
            $transfers = Transfers::where('datetime', 'LIKE', $from . '%');
        }

        $transfers = $transfers -> get();

        return View::make('pages.reports.transfersReport')  -> with('branches', $branches)
                                                    -> with('transfers', $transfers)
                                                    -> with('dStart', $from)
                                                    -> with('dEnd', $to);;
    }

    public function getDailySales(){
        $datenow           = new DateTimeImmutable();
        $dn                = $datenow -> format("Y-m-d");
        $dayPlusOne        = $datenow -> sub(new DateInterval('P1D')) -> format("Y-m-d");
        $dayPlusTwo        = $datenow -> sub(new DateInterval('P2D')) -> format("Y-m-d");
        $dayPlusThree      = $datenow -> sub(new DateInterval('P3D')) -> format("Y-m-d");
        $dayPlusFour       = $datenow -> sub(new DateInterval('P4D')) -> format("Y-m-d");

        $sale1 = Sale::getTotalSaleOnDate($dn);
        $sale2 = Sale::getTotalSaleOnDate($dayPlusOne);
        $sale3 = Sale::getTotalSaleOnDate($dayPlusTwo);
        $sale4 = Sale::getTotalSaleOnDate($dayPlusThree);
        $sale5 = Sale::getTotalSaleOnDate($dayPlusFour);

        if(Request::ajax()){
            $sales = array();

            foreach ($sale1 as $s) {
                $sales[0]['date'] = $dn;
                $sales[0] = $s -> amount;
            }

            foreach ($sale2 as $s) {
                $sales[1]['date'] = $dayPlusOne;
                $sales[1] = $s -> amount;
            }

            foreach ($sale3 as $s) {
                $sales[2]['date'] = $dayPlusTwo;
                $sales[2] = $s -> amount;
            }

            foreach ($sale4 as $s) {
                $sales[3]['date'] = $dayPlusOneThree;
                $sales[3] = $s -> amount;
            }

            foreach ($sale5 as $s) {
                $sales[4]['date'] = $dayPlusFour;
                $sales[4] = $s -> amount;
            }

            echo json_encode($sales);
        } else {
            echo "Lost?";
        }
    }
}