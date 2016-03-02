@extends('layouts.master')

@section('content')
    
    <div class="row">
        <div class="col-xs-8 col-xs-offset-2">
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
            {{
                Form::open(array(
                            'route' => 'transfer-item',
                            'class' => 'form-horizontal',
                            'id' => 'form-transfer',
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
                        <input type="text" class="form-control" placeholder="Product code" name="input-code" class="typeahead" id="input-pcode">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Sale Type</label>
                    <div class="col-xs-7">
                        <select name="input-saleType" class="form-control" id="input-type">
                            <option value="ret">Retail</option>
                            <option value="pkg">Package</option>                            
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">In/Out</label>
                    <div class="col-xs-7">
                        <label for=""><input class="radio-inline input-ioo" type="radio" name="input-ioo" value="In" required>In</label>
                        <br />
                        <label for=""><input class="radio-inline input-ioo" type="radio" name="input-ioo" value="Out">Out</label>
                    </div>
                </div>

                <div class="form-group hidden" id="newGroup">
                    <label class="col-xs-5" for=""><a href="#" class="isTooltip" title="Is this a new item in the inventory? (New lot#,expiry,etc.)">New Item?</a></label>
                    <div class="col-xs-7">
                        <label for=""><input class="checkbox-inline input-ioo" type="checkbox" id="chkNew"> Yes</label>
                    </div>
                </div>

                <div class="form-group hidden" id="lotGroup">
                    <label class="col-xs-5" for="">Lot #</label>
                    <div class="col-xs-7">
                        <input type="text" class="form-control" name="input-lot" id="input-lot">
                    </div>
                </div>

                <div class="form-group hidden" id="expGroup">
                    <label class="col-xs-5" for="">Expiry</label>
                    <div class="col-xs-7">
                        <input type="date" class="form-control expiry-picker" name="input-exp" id="input-exp">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">From</label>
                    <div class="col-xs-7">
                        <select name="input-from" class="form-control" id="input-from" disabled>
                            <optgroup label="Branches">
                                @foreach($branches as $b)
                                    @if($b -> name == Session::get('branch'))
                                        <option value="{{$b -> name}}" selected>{{$b -> name}}</option>
                                    @else
                                        <option value="{{$b -> name}}">{{$b -> name}}</option>
                                    @endif
                                @endforeach
                            </optgroup>
                            <optgroup label="Suppliers">
                                @foreach($suppliers as $s)
                                    <option value="{{$s -> code}}">{{$s -> name}}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">To</label>
                    <div class="col-xs-7">
                        <select name="input-to" class="form-control" id="input-to" disabled>
                            @foreach($branches as $b)
                                    @if($b -> name == Session::get('branch'))
                                        <option value="{{$b -> name}}" selected>{{$b -> name}}</option>
                                    @else
                                        <option value="{{$b -> name}}">{{$b -> name}}</option>
                                    @endif
                                @endforeach
                            </optgroup>
                            <optgroup label="Suppliers">
                                @foreach($suppliers as $s)
                                    <option value="{{$s -> code}}">{{$s -> name}}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Available Quantity (P)</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" name="transfer[av_quantity_p]" id="item-qtyp" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Available Quantity (R)</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" name="transfer[av_quantity_k]" id="item-qtyr" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Quantity</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" name="input-qty" id="input-qty" min="0" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-xs-4 center-block">
                        <button class="btn btn-primary btn-lg">Transfer</button>
                    </div>
                </div>
            {{ Form::close() }}
        </div>

@stop

@section('assets')

    {{HTML::script('assets/js/typeahead.js')}}
    {{HTML::script('assets/js/transfer.js')}}

    {{HTML::style('assets/css/typeahead.css')}}

@stop