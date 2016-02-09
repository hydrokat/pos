@extends('layouts.master')

@section('content')
    {{Debugbar::info($codes)}}

    <div class="row">
        <div class="col-xs-4">
            @if (Session::has('message'))
                <div class="alert alert-success" role="alert">
                    {{Session::get('message')}}
                </div>
            @endif
            
            @if ($errors -> has())
                <div class="alert alert-danger" role="alert">
                    @foreach($errors -> all() as $error)        
                        {{$error}} <br />
                    @endforeach
                </div>
            @endif

            @if(Session::has('invoice'))
                <div class="rounded-box" role="alert">
                    {{link_to('/invoice/' . Session::get('invoice'), "View Invoice Here", array('target' => '_blank'))}}
                </div>                
            @endif
            {{
                Form::open(array(
                            'route' => 'newSale',
                            'class' => 'form-horizontal',
                            'id'    => 'form-sale'
                            ))
            }}
                <div class="form-group">
                    <label class="col-xs-5" for="">Invoice #</label>
                    <div class="col-xs-7" id="invoice">
                        <input type="text" class="form-control" value="{{Session::get('invoice')}}" placeholder="NEW INVOICE" title="Click to clear." name="invoice" id="input-invoice" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Product Code</label>
                    <div class="col-xs-7" id="pcode">
                        <input type="text" class="typeahead form-control" placeholder="Product code" name="code" id="input-pcode">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Sale Type</label>
                    <div class="col-xs-7">
                        <select name="type" id="input-stype" class="form-control">
                            <option value="pkg">Package</option>
                            <option value="ret" selected>Retail</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Quantity</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" placeholder="Quantity" name="qty" id="input-qty" min='0' max='0'>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Discount</label>
                    <div class="col-xs-7">
                        <input type="text" class="form-control" placeholder="Discount" name="dsc" value="0" step="0.01" id="input-dsc">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-xs-4">
                        <button class="btn btn-primary btn-lg" id="sell-item">Sell</button>
                    </div>
                </div>

                <input type="hidden" class="form-control" name="lot" id="input-lot">
                <input type="hidden" class="form-control" name="exp" id="input-exp">
                <input type="hidden" class="form-control" name="pPkg" value="00" id="price-pkg">
                <input type="hidden" class="form-control" name="pRet" value="00" id="price-ret">
                <input type="hidden" class="form-control" name="amt" value="00" id="input-amt">
                <input type="hidden" class="form-control" name="totAmt" value="00" id="input-totAmt">
                <input type="hidden" class="form-control" name="totDsc" value="00" id="input-totDsc">
            {{ Form::close() }}
        </div>

        <div class="col-xs-8 rounded-box">
            <div class="row">
                <label class="col-xs-3 control-label">Item Code</label>
                <div class="col-xs-9">
                  <span class="form-control-static" id="item-code">N/A</span>
                </div>
            </div>

            <div class="row">
                <label class="col-xs-3 control-label">Lot No</label>
                <div class="col-xs-9">
                  <span class="form-control-static" id="item-lotno">N/A</span>
                </div>
            </div>

            <div class="row">
                <label class="col-xs-3 control-label">Item Name</label>
                <div class="col-xs-9">
                  <span class="form-control-static" id="item-name">N/A</span>
                </div>
            </div>

            <div class="row">
                <label class="col-xs-3 control-label">Item Description</label>
                <div class="col-xs-9">
                  <span class="form-control-static" id="item-desc">N/A</span>
                </div>
            </div>

            <div class="row">
                <label class="col-xs-3 control-label">Expiry Date</label>
                <div class="col-xs-9">
                  <span class="form-control-static" id="item-exp">N/A</span>
                </div>
            </div>

            <div class="row">
                <label class="col-xs-3 control-label">Available Quantity (P)</label>
                <div class="col-xs-9">
                  <span class="form-control-static" id="item-qtyp">N/A</span>
                </div>
            </div>

            <div class="row">
                <label class="col-xs-3 control-label">Available Quantity (R)</label>
                <div class="col-xs-9">
                  <span class="form-control-static" id="item-qtyr">N/A</span>
                </div>
            </div>

            <div class="row">
                <label class="col-xs-3 control-label">Package Price</label>
                <div class="col-xs-9">
                  <span class="form-control-static" id="item-pricep">N/A</span>
                </div>
            </div>

            <div class="row">
                <label class="col-xs-3 control-label">Retail Price</label>
                <div class="col-xs-9">
                  <span class="form-control-static" id="item-pricer">N/A</span>
                </div>
            </div>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-xs-12 rounded-box">
            <div class="row">
                <label class="col-xs-3 control-label">Sale Type</label>
                <div class="col-xs-9">
                  <span class="form-control-static" id="type">Package/Retail</span>
                </div>
            </div>
            <div class="row">
                <label class="col-xs-3 control-label">Quantity</label>
                <div class="col-xs-9">
                  <span class="form-control-static" id="qty">000</span>
                </div>
            </div>
            <div class="row">
                <label class="col-xs-3 control-label">Discount</label>
                <div class="col-xs-9">
                  <span class="form-control-static" id="dsc">P 000.00</span>
                </div>
            </div>
            <div class="row">
                <label class="col-xs-3 control-label">Total Price</label>
                <div class="col-xs-9">
                  <span class="form-control-static" id="tot">P 000.00</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4"><a href="" id="invoice-link" class="hidden" target="_blank">View Invoice Here</a></div>
        <div class="col-xs-12" id="invoice-display">
            <table id="invoice-table" class="table table-striped">
            </table>
        </div>
        <div class="col-xs-12">
            <button class="btn btn-primary btn-lg" id="btn-saleConfirm">Confirm Sale</button>
        </div>        
    </div>

@stop

@section('assets')
    
    {{HTML::script('assets/js/typeahead.js')}}
    {{HTML::script('assets/js/salescript.js')}}

    {{ HTML::style('assets/css/typeahead.css') }}

@stop