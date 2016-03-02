<?php

class SalesController extends BaseController {
    public $restful = true;

    public function getSale() {
        $codes = DB::table('items') -> lists('p_code');

        return View::make('pages.sale') -> with('codes', $codes);
    }

    public function getCart() {
        echo json_encode(Session::get('sale.data'));
    }

    public function clearCart() {
        Session::forget('sale.data');
    }

    public function postSale(){
        $messages = array(
            'code.required' => 'Product code is required.',
            'qty.required'  => 'Please input quantity.',
            'qty.integer'   => 'Quantity should be an integer.',
            'type.required' => 'Product code is required.',
            'dsc.required'  => 'Provide a discount. Input 0 if there is no discount.',
        );

        $validator = Validator::make( Input::all(),
            array(
                'code'  => 'required',
                'qty'   => 'required|integer',
                'type'  => 'required',
                'dsc'   => 'required',
            )
        , $messages);

        if ($validator->fails()) {
            return Redirect::route('sale')
                            -> withErrors($validator)
                            -> withInput();
        } else {
            $saleData['code'] = htmlspecialchars(Input::get('code'));
            $saleData['qty']  = Input::get('qty');
            $saleData['amt']  = Input::get('totAmt');
            $saleData['type'] = Input::get('type');
            $saleData['dsc']  = Input::get('totDsc');
            $saleData['lot']  = Input::get('lot');
            $saleData['exp']  = Input::get('exp');
            $saleData['inv']  = Input::get('invoice');

            Session::push('sale.data', $saleData);

            echo json_encode(Session::get('sale.data'));
        }
    }

    public function removeSaleData() {
        $i = Input::get('i');
        $saleArray = Session::get('sale.data');
        Session::forget('sale.data');
        unset($saleArray[$i]);
        array_filter($saleArray);

        Session::put('sale.data', $saleArray);
        echo json_encode($saleArray);
    }

    public function confirmSale() {
        //echo json_encode(Session::get('sale.data'));

        $saleArray = Session::get('sale.data');

        foreach ($saleArray as $sData) {
            $code = $sData['code'];
            $qty  = $sData['qty'];
            $amt  = $sData['amt'];
            $type = $sData['type'];
            $dsc  = $sData['dsc'];
            $lot  = $sData['lot'];
            $exp  = $sData['exp'];
            $inv  = isset($invoiceNumber) ? $invoiceNumber : '';

            $loop = 0;

            do{
                if($loop == 1){

                    if($sData['type'] == 'ret'){
                        $itemInventory = Inventory::where('p_code', '=', $sData['code']) -> where('retail', '>', 0) -> first();
                    } else {
                        $itemInventory = Inventory::where('p_code', '=', $sData['code']) -> where('packages', '>', 0) -> first();
                    }

                    $lot = $itemInventory -> lotNo;
                    $exp = $itemInventory -> expiry;
                    
                } else {
                    $itemInventory = Inventory::where('p_code', '=', $sData['code']) -> where('lotNo', '=', $sData['lot']) -> where('expiry', '=', $sData['exp']) -> first();
                }

                $itemQuantity = $sData['type'] == 'ret' ? $itemInventory -> retail : $itemInventory -> packages;

                $sell_qty = $qty > $itemQuantity ? $itemQuantity : $qty;
                $qty -= $sell_qty;

                $invoiceNumber = Sale::insertSale($code, $lot, $exp, $type, $sell_qty, $amt, $dsc, $inv);
                $inv           = $invoiceNumber;

                $action = "User has sold " . $sell_qty . " " . $type ." with code " . $code . " for " . $amt . " with " . $dsc . " discount. Invoice#: " . $inv;
                $this -> eventLog(Auth::user() -> name, $action);

                $loop = 1;
            } while ($qty > 0);
        }

        echo json_encode($invoiceNumber);

        Session::forget('sale.data');
    }
}