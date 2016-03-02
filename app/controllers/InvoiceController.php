<?php

class InvoiceController extends BaseController {

    public function getInvoice(){
        $sales = Sale::groupBy('invoiceNumber')->get();

        return View::make('pages.reports.invoice')
                                    -> with('sales', $sales);
    }

    public function generateInvoice($invoiceNumber)
    {
        $sales = Sale::where('invoiceNumber', '=', $invoiceNumber) -> get();

        $tDsc = 0;
        $tAmt = 0;

        foreach ($sales as $s) {
            $tDsc += $s -> discount;
            $tAmt += $s -> amount;
            $cashierName = $s->cashierName;
            $dt = $s->datetime;
        }

        return View::make('invoice.sale') 
                                    -> with('invoiceNumber', $invoiceNumber)
                                    -> with('cashier', $cashierName)
                                    -> with('date', $dt)
                                    -> with('tDsc', $tDsc)
                                    -> with('tAmt', $tAmt)
                                    -> with('sales', $sales);
    }

    public function getDinvoice(){
        $transfers = Transfers::groupBy('invoiceNumber')->get();

        return View::make('pages.reports.dinvoice')
                                    -> with('transfers', $transfers);
    }

    public function generateDinvoice($invoiceNumber)
    {
        $transfers = Transfers::where('invoiceNumber', '=', $invoiceNumber) -> get();

        $tDsc = 0;
        $tAmt = 0;

        foreach ($transfers as $t) {
            $cashierName = $t->cashierName;
            $from = $t->from;
            $to = $t->to;
            $dt = $t->datetime;
            $iio = $t->inOrOut;
            $from = $t->from;
            $to = $t->to;
        }

        return View::make('invoice.transfers') 
                                    -> with('invoiceNumber', $invoiceNumber)
                                    -> with('cashier', $cashierName)
                                    -> with('from', $from)
                                    -> with('to', $to)
                                    -> with('date', $dt)
                                    -> with('transfers', $transfers);
    }


}
