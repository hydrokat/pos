<?php

class ItemsController extends BaseController {

    public function getItems(){
        $inventory = Inventory::getInventory();
        $types     = ItemTypes::all();
        $suppliers = Supplier::all();

        return View::make('pages.items') -> with('suppliers', $suppliers)
                                         -> with('itemTypes', $types)
                                         -> with('inventory', $inventory);
    }

    public function getPCodes(){
        if(Request::ajax()){
            $codes = DB::table('items') -> distinct()  -> lists('p_code');
            echo json_encode($codes);
        } else {
            echo "Hi, these are your items: <br />";

            $codes = DB::table('items') -> distinct() -> lists('p_code');
            echo json_encode($codes);
        }
    }

    public function getTransfer(){
        $branches = Branches::all();
        $suppliers = Supplier::all();
        return View::make('pages.transfer') -> with('suppliers', $suppliers)
                                            -> with('branches', $branches);
    }

    public function ajaxItem() {
        $code = htmlspecialchars(Input::get('pcode'));
        $lot  = Input::get('lot');
        $exp  = Input::get('exp');
        $getall  = Input::has('getall') ? Input::get('getall') : false;

        $branchid = Session::get('branchid');
        if($code != '') {
            if(Request::ajax()){
                if($lot == '' || $exp == '') {
                    if($getall) {
                        $item = DB::table('items') -> join('inventory', 'items.p_code', '=', 'inventory.p_code')
                            -> whereRaw('retail = (SELECT MIN(retail) FROM tbl_pos_inventory WHERE retail > 0 AND p_code = "' . $code . '")')
                            -> where('items.p_code', '=' , $code)
                            -> where('inventory.branchID', '=', $branchid)
                            -> first();

                        $item -> sum = Inventory::getQuantity($code);
                    } else{
                        $item = DB::table('items') -> join('inventory', 'items.p_code', '=', 'inventory.p_code')
                            -> whereRaw('retail = (SELECT MIN(retail) FROM tbl_pos_inventory WHERE retail > 0 AND p_code = "' . $code . '")')
                            -> where('items.p_code', '=' , $code)
                            -> where('inventory.branchID', '=', $branchid)
                            -> first();
                    }
                } else {
                    $item = DB::table('items') -> join('inventory', function($j) use ($lot, $exp){
                                $j -> on('items.p_code', '=', 'inventory.p_code')
                                   -> where('inventory.lotNo', '=', $lot)
                                   -> where('inventory.expiry', '=', $exp);
                            })
                            -> where('items.p_code', '=' ,$code)
                            -> where('items.lotNo', '=', $lot)
                            -> where('items.expiry', '=', $exp)
                            -> where('inventory.branchID', '=', $branchid)
                            -> first();
                }
                if(is_null($item)){
                    echo "No item has been found with Pcode: " . $code . "<br> Lot: " . $lot . "<br> Expiration: " . $exp;
                } else {
                    echo json_encode($item);
                }
            } else {
                echo "Hi, this is your item: <br />";

                if($lot == '' || $exp == '') {
                    $item = DB::table('items') -> join('inventory', 'items.p_code', '=', 'inventory.p_code')
                            -> whereRaw('retail = (SELECT MIN(retail) FROM tbl_pos_inventory WHERE retail > 0 AND p_code = "' . $code . '")')
                            -> where('items.p_code', $code)
                            -> where('inventory.branchID', '=', $branchid)
                            -> first();
                } else {
                    $item = DB::table('items') -> join('inventory', function($j) use ($lot, $exp){
                                $j -> on('items.p_code', '=', 'inventory.p_code')
                                   -> where('inventory.lotNo', '=', $lot)
                                   -> where('inventory.expiry', '=', $exp);
                            })
                            -> where('items.p_code', $code)
                            -> where('items.lotNo', '=', $lot)
                            -> where('items.expiry', '=', $exp)
                            -> where('inventory.branchID', '=', $branchid)
                            -> first();
                }

                echo json_encode($item);
            }
        } else {
            echo "Request not granted.";
        }
    }

    public function addItem(){
        $messages = array(
            'input-pcode.unique' => 'This item already exists in the database.',
        );
        $validator = Validator::make( Input::all(),
            array(
                'input-pcode'   => 'required|unique:items,p_code,NULL,expiry' . Input::get('input-expiry') . ',lotNo,' . Input::get('input-lot'),
                'input-name'    => 'required',
                'input-pRet'    => 'required',
                'input-pPkg'    => 'required',
                'input-qtyRet'    => 'required|numeric',
                'input-qtyPkg'    => 'required|numeric',
            )
        , $messages);

        if ($validator->fails()) {
            return Redirect::route('items')
                                -> withInput()
                                -> withErrors($validator);
        } else {
            $item = new Item;
            $inv = new Inventory;

            $item -> p_code              = Input::get('input-pcode');
            $item -> name                = Input::get('input-name');
            $item -> desc                = nl2br(Input::get('input-desc'));
            $item -> category            = Input::get('input-cat');
            $item -> expiry              = Input::get('input-exp');
            $item -> lotNo               = Input::get('input-lot');
            $item -> branchID            = Session::get('branchid');
            $item -> inventory_threshold = Input::get('input-invThresh');
            $item -> price_retail        = Input::get('input-pRet');
            $item -> price_package       = Input::get('input-pPkg');

            $inv -> p_code               = Input::get('input-pcode');
            $inv -> lotNo                = Input::get('input-lot');
            $inv -> expiry              = Input::get('input-exp');
            $inv -> packages             = Input::get('input-qtyRet');
            $inv -> retail               = Input::get('input-qtyPkg');
            $inv -> supplier             = Input::get('input-supplier');
            $inv -> acquisition_price    = Input::get('input-pAcq');

            $item -> save();
            $inv -> save();

            $this -> eventLog(Auth::user() -> username, "An item has been added. Product Code: " . Input::get('input-pcode'));

            return Redirect::route('items')
                                 -> with('message', 'Item has been added!');
        }
    }

    public function editItem() {
        $validator = Validator::make( Input::all(),
            array(
                'input-pcode'   => 'required',
                'input-name'    => 'required'
            )
        );

        if ($validator->fails()) {
            return Redirect::route('items')
                                -> withErrors($validator);
        } else {
            $item   = Item::where('p_code', '=', Input::get('input-pcode')) -> where('lotNo', '=', Input::get('input-oldLot')) -> where('expiry', '=', Input::get('input-oldExp')) -> first();
            $inv    = Inventory::where('p_code', '=', Input::get('input-pcode')) -> where('lotNo', '=', Input::get('input-oldLot')) -> where('expiry', '=', Input::get('input-oldExp')) -> first();

            $item -> name                = Input::get('input-name');
            $item -> desc                = nl2br(Input::get('input-desc'));
            $item -> category            = Input::get('input-cat');
            $item -> expiry              = Input::get('input-exp');
            $item -> lotNo               = Input::get('input-lot');
            $item -> inventory_threshold = Input::get('input-invThresh');
            $item -> price_retail        = Input::get('input-pRet');
            $item -> price_package       = Input::get('input-pPkg');

            $inv -> packages             = Input::get('input-qtyPkg');
            $inv -> retail               = Input::get('input-qtyRet');
            $inv -> expiry               = Input::get('input-exp');
            $inv -> lotNo                = Input::get('input-lot');
            $inv -> supplier             = Input::get('input-supplier');
            $inv -> acquisition_price    = Input::get('input-pAcq');

            $msg = "An item has been modified. Product Code: " . Input::get('input-pcode');
            $msg .= "<br /> Changes: <br />";
            $msg .= "<ul>";

            $msg .= $item -> isDirty('name') ? "<li>Name: " . $item -> getOriginal('name') . " -> " . Input::get('input-name') . "</li>" : "";
            $msg .= $item -> isDirty('desc') ? "<li>Description: " . $item -> getOriginal('desc') . " -> " . nl2br(Input::get('input-desc')) . "</li>" : "";
            $msg .= $item -> isDirty('lotNo') ? "<li>Lot #: " . $item -> getOriginal('lotNo') . " -> " . Input::get('input-lot') . "</li>" : "";
            $msg .= $item -> isDirty('expiry') ? "<li>Expiry: " . $item -> getOriginal('expiry') . " -> " . Input::get('input-exp') . "</li>" : "";
            $msg .= $item -> isDirty('category') ? "<li>Category: " . $item -> getOriginal('category') . " -> " . Input::get('input-cat') . "</li>" : "";
            $msg .= $item -> isDirty('inventory_threshold') ? "<li>Inventory Threshold: " . $item -> getOriginal('inventory_threshold') . " -> " . Input::get('input-invThresh') . "</li>" : "";
            $msg .= $item -> isDirty('acquisition_price') ? "<li>Price (Acquisition): " . $item -> getOriginal('acquisition_price') . " -> " . Input::get('input-pAcq') . "</li>" : "";
            $msg .= $item -> isDirty('price_retail') ? "<li>Price (Retail): " . $item -> getOriginal('price_retail') . " -> " . Input::get('input-pRet') . "</li>" : "";
            $msg .= $item -> isDirty('price_package') ? "<li>Price (Package): " . $item -> getOriginal('price_package') . " -> " . Input::get('input-pPkg') . "</li>" : "";
            $msg .= $inv  -> isDirty('retail') ? "<li>Quantity (Retail): " .    $inv -> getOriginal('retail') . " -> " . Input::get('input-qtyRet') . "</li>" : "";
            $msg .= $inv  -> isDirty('packages') ? "<li>Quantity (Package): " . $inv -> getOriginal('packages') . " -> " . Input::get('input-qtyPkg') . "</li>" : "";

            $msg .= "</ul>";

            $item   -> save();
            $inv    -> save();
            $this -> eventLog(Auth::user() -> username, $msg);

            return Redirect::route('items')
                                 -> with('message', 'Item has been updated!');
        }
    }

    public function transferItem() {
        $errorMsg = array(
            'input-code.required' => 'Product code is required.',
            'input-lot.required'  => 'Lot number is required',
            'input-exp.required'   => 'Expiry date is required',
        );
        $validator = Validator::make( Input::all(),
            array(
                'input-code'   => 'required',
                'input-lot'   => 'required',
                'input-exp'   => 'required',
                'input-qty'    => 'required'
            )
        , $errorMsg);

        $date   = new DateTime("now");
        $p_code = htmlspecialchars(Input::get('input-code'));
        $from   = Input::get('input-from');
        $to     = Input::get('input-to');
        $type   = Input::get('input-saleType');
        $qty    = Input::get('input-qty');
        $ioo    = Input::get('input-ioo');
        $exp    = Input::get('input-exp');
        $lot    = Input::get('input-lot');

        $iooBool = $ioo == "In" ? 1 : 0;

        if (!$validator -> fails()) {
            $transfer = new Transfers;

            $transfer -> datetime    = $date;
            $transfer -> cashierName = Auth::user() -> name;
            $transfer -> from        = $from;
            $transfer -> to          = $to;
            $transfer -> p_code      = $p_code;
            $transfer -> lotNo       = $lot;
            $transfer -> expiry      = $exp;
            $transfer -> type        = $type;
            $transfer -> quantity    = $qty;
            $transfer -> inOrOut     = $iooBool;
            $transfer -> invoiceNumber  = Input::get('invoice') != '' ? Input::get('invoice') : date("Ymd") . "-" . sprintf("%05d", Transfers::count() + 1);

            $invoiceNumber = $transfer -> invoiceNumber;

            $transfer -> save();

            $msg = "An item has been transferred. ";
            if($ioo == "In"){
                $msg .= "<br />Product Code: " . $p_code;
                $msg .= "<br />Type(Quantity): " . $type . "(" . $qty . ")";
                $msg .= "<br />In Or Out: " . $iooBool;
                $msg .= "<br />From " . $from . " to " . $to;
                //in
                $item = Item::where('p_code', '=', $p_code) -> where('lotNo', '=', $lot) -> where('expiry', '=', $exp) -> get();

                if($item -> isEmpty()){
                    //new item incoming
                    $item = new Item;
                    $inventory = new Inventory;

                    $expiry = $exp;

                    $item -> p_code = strtoupper($p_code);
                    $item -> lotNo = $lot;
                    $item -> expiry = $expiry;

                    $inventory -> p_code = strtoupper($p_code);
                    $inventory -> lotNo = $lot;
                    $inventory -> expiry = $expiry;
                    $inventory -> branchID = Session::get('branchid');

                    if($type == 'pkg'){
                        $inventory -> packages = $qty;
                    } else {
                        $inventory -> retail = $qty;
                    }

                    $inventory -> save();
                    $item -> save();
                } else {
                    //item exists in database
                    $inventory = Inventory::where('p_code', '=', $p_code) -> where('lotNo', '=', $lot) -> where('expiry', '=', $exp) -> first();

                    if($type == 'pkg'){
                        $inventory -> packages += $qty;
                    } else {
                        $inventory -> retail += $qty;
                    }

                    $inventory -> save();
                }
            } else {
                //out
                $inventory = Inventory::where('p_code', '=', $p_code) -> where('lotNo', '=', $lot) -> where('expiry', '=', $exp) -> first();

                if($type == 'pkg'){
                    $inventory -> packages -= $qty;
                } else {
                    $inventory -> retail -= $qty;
                }

                $inventory -> save();
            }
            $branches = Branches::all();

            $this -> eventLog(Auth::user() -> username, $msg);

            return Redirect::route('getTransfer') -> with('branches', $branches)
                                                  -> with('invoice', $invoiceNumber)
                                                  -> with('message', "Transfer complete!");
        } else {
            $branches = Branches::all();
            return Redirect::route('getTransfer') -> with('branches', $branches)
                                                  -> withInput()
                                                  -> withErrors($validator);
        }
    }

    public function deleteItem($pcode, $lot, $exp) {
        $item = Item::where('p_code', '=', $pcode) -> where('lotNo', '=', $lot) -> where('expiry', '=', $exp) -> where('branchID', '=', Session::get('branchid')) -> delete();

        $msg = "Item has been deleted. Product code: " . $pcode;
        $this -> eventLog(Auth::user() -> username, $msg);
        echo $msg;
    }

    public function undoDeleteItem($pcode, $lot, $exp) {
        $item = Item::onlyTrashed() -> where('p_code', '=', $pcode) -> where('lotNo', '=', $lot) -> where('expiry', '=', $exp) -> where('branchID', '=', Session::get('branchid')) -> get();
        $item -> restore();

        $msg = "Item has been restored. Product code: " . $pcode;
        $this -> eventLog(Auth::user() -> username, $msg);
        echo $msg;
    }
}
