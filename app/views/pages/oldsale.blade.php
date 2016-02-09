@extends('layouts.master')

@section('content')
    
    <div class="row">
        <div class="col-xs-4">
            {{
                Form::open(array(
                            'url' => '#',
                            'class' => 'form-horizontal'
                            ))
            }}
                <div class="form-group">
                    <label class="col-xs-5" for="">Date</label>
                    <div class="col-xs-7">
                        <input type="date" class="form-control" placeholder="Product code" name="sale[date]">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Product Code</label>
                    <div class="col-xs-7">
                        <input type="text" class="form-control" placeholder="Product code" name="sale[code]">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Sale Type</label>
                    <div class="col-xs-7">
                        <select name="sale[type]" id="" class="form-control">
                            <option value="">Package</option>
                            <option value="">Retail</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Quantity</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" placeholder="Product code" name="sale[quantity]">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-xs-4 pull-right">
                        <button class="btn btn-primary btn-lg" onclick="return confirm('Verify sale data')">Sell</button>
                    </div>
                </div>
            {{ Form::close() }}
        </div>

        <div class="col-xs-8 rounded-box">
            <div class="row">
                <label class="col-xs-3 control-label">Item Code</label>
                <div class="col-xs-9">
                  <p class="form-control-static">R-Sample-050</p>
                </div>
            </div>

            <div class="row">
                <label class="col-xs-3 control-label">Item Description</label>
                <div class="col-xs-9">
                  <p class="form-control-static">Sample Description</p>
                </div>
            </div>

            <div class="row">
                <label class="col-xs-3 control-label">Available Quantity (P)</label>
                <div class="col-xs-9">
                  <p class="form-control-static">9999</p>
                </div>
            </div>

            <div class="row">
                <label class="col-xs-3 control-label">Available Quantity (K)</label>
                <div class="col-xs-9">
                  <p class="form-control-static">9999</p>
                </div>
            </div>

            <div class="row">
                <label class="col-xs-3 control-label">Package Price</label>
                <div class="col-xs-9">
                  <p class="form-control-static">P 999.00</p>
                </div>
            </div>

            <div class="row">
                <label class="col-xs-3 control-label">Retail Price</label>
                <div class="col-xs-9">
                  <p class="form-control-static">P 999.00</p>
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
                  <p class="form-control-static">Package/Retail</p>
                </div>
            </div>
            <div class="row">
                <label class="col-xs-3 control-label">Quantity</label>
                <div class="col-xs-9">
                  <p class="form-control-static">999</p>
                </div>
            </div>
            <div class="row">
                <label class="col-xs-3 control-label">Discount</label>
                <div class="col-xs-9">
                  <p class="form-control-static">P 999.00</p>
                </div>
            </div>
            <div class="row">
                <label class="col-xs-3 control-label">Total Price</label>
                <div class="col-xs-9">
                  <p class="form-control-static">P 999.00</p>
                </div>
            </div>
        </div>
    </div>

@stop

@section('assets')

@stop