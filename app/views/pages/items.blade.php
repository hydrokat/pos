@extends('layouts.master')

@section('content')
    {{ Debugbar::info($inventory) }}
    <div class="row">
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
    </div>
    <div class="row">
      <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal-add"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Item</button>
    </div>    
    <div class="row">
      <div class="col-xs-6">
        <h4>Legend:</h4>
        <ul>
          <li><span class="bg-red">Stocks Running Out</span></li>
          <li><span class="bg-yellow">Expired/ing</span></li>
          <li><span>Med - Medicine</span></li>
          <li><span>Msp - Medical Supplies</span></li>
          <li><span>Lab - Lab Equipment</span></li>
          <li><span>Csm - Cosmetics</span></li>
          <li><span>Meq - Medical Equipment</span></li>
          <li><span>Lab Supplies - Cosmetics</span></li>
        </ul>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-6">        
        <button type="button" id="btn-itemFilters" class="btn btn-default btn-sm">
          <span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filters
        </button>
        <ul class="item-filters">
          <li><a href="#" data-sort="all" id="filter-stock">All</a></li>
          <li><a href="#" data-sort="stk" id="filter-stock">Stocks Running Out</a></li>
          <li><a href="#" data-sort="exp" id="filter-exp">Expired/ing</a></li>
        </ul>
      </div>
    </div>
    <div class="row">
      <div class="form-group form-inline">
        <div class="input-group">
          <span class="input-group-addon">Search</span>
          <input type="text" class="form-control" placeholder="Product Code" id="txt-search">
        </div>
      </div>
      <h2 class="sub-header">Items</h2>
      <ul class="nav nav-tabs">
        <li role="presentation" class="tab-item active"><a href="#" data-code="all">All</a></li>
        @foreach ($itemTypes as $type)
          <li role="presentation" class="tab-item"><a href="#" data-code="{{$type -> code}}">{{$type -> name}}</a></li>
        @endforeach
      </ul>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Code</th>
              <th>Lot #</th>
              <th>Expiry Date</th>
              <th>Category</th>
              <th>Name</th>
              <th>Packages Left</th>
              <th>Retail Left</th>
              <th>Package/Retail Price</th>
              <th>Acquisition Price</th>
              <th>Supplier</th>
              <!-- <th>Last Updated</th> -->
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($inventory as $item)
              @if($item -> retail <= $item -> inventory_threshold)
                <tr class="bg-red">                  
              @elseif(strtotime($item -> expiry) - time()-(60*60*24) <= 190 * 24 * 60 * 60)
                <tr class="bg-yellow">
              @else
                <tr>
              @endif
              <td>{{$item -> p_code}}</td>
              <td>{{$item -> lotNo}}</td>                  
              <td>{{$item -> expiry}}</td>
              <td>{{ucfirst($item -> category)}}</td>
              <td>{{$item -> name}}</td>
              <td>{{$item -> packages}}</td>
              <td>{{$item -> retail}}</td>
              <td>{{$item -> price_package}}/{{$item -> price_retail}}</td>
              <td>{{$item -> acquisition_price or '0'}}</td>
              <td>{{$item -> supplier or 'N/A'}}</td>
              <!-- <td>{{$item -> updated_at}}</td> -->
              <td>                    
                <button class="btn btn-warning btn-xs btn-editItem" data-toggle="modal" data-target="#modal-edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                @if(Auth::user()->role <= 2)
                    <button class="btn btn-danger btn-xs btn-deleteItem" data-exp="{{$item -> expiry}}" data-lot="{{$item -> lotNo}}" data-pcode="{{$item -> p_code}}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                @endif
                <!-- <button class="btn btn-info btn-xs btn-searchItem" data-pcode="{{$item -> p_code}}"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button> -->
              </td>
            </tr>

            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Add Item <button class="btn btn-warning btn-xs" id="addItemReminder"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span></button></h4>
          </div>
          {{
            Form::open(array(
              'route' => 'newItem',
              'class' => 'form-horizontal'
            ))
          }}          
          <div class="modal-body">
                <div class="form-group">
                    <label class="col-xs-5" for="">Product Code</label>
                    <div class="col-xs-7" id="pcode">
                        <input type="text" class="typeahead form-control" placeholder="Product code" name="input-pcode" id="input-pcode" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Item Name</label>
                    <div class="col-xs-7" id="pcode">
                        <input type="text" class="typeahead form-control" placeholder="Product Name" name="input-name" id="input-name" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Item Description</label>
                    <div class="col-xs-7" id="pcode">
                        <textarea type="text" class="typeahead form-control" name="input-desc" id="input-desc" required>Put item description here </textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Supplier</label>
                    <div class="col-xs-7">
                        <select name="input-supplier" class="form-control" id="input-from">
                            <optgroup label="Suppliers">
                                @foreach($suppliers as $s)
                                    <option value="{{$s -> code}}">{{$s -> name}}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Category</label>
                    <div class="col-xs-7">
                        <select name="input-cat" id="input-cat" class="form-control">
                          @foreach ($itemTypes as $type)
                            <option value="{{$type -> code}}" selected>{{$type -> name}}</option>
                          @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Lot No:</label>
                    <div class="col-xs-7" id="pcode">
                        <input type="text" class="typeahead form-control" placeholder="Lot Number" name="input-lot" id="input-lot" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Expiry</label>
                    <div class="col-xs-7" id="pcode">
                        <input type="date" class="typeahead expiry-picker form-control" placeholder="Expiry Date" name="input-exp" id="input-exp1" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Inventory Threshold</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" placeholder="Quantity" name="input-invThresh" id="input-invThresh" value="5" required min="1">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Package Quantity</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" placeholder="Quantity" name="input-qtyPkg" id="input-qtyPkg" value="0" required min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Retail Quantity</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" placeholder="Quantity" name="input-qtyRet" id="input-qtyRet" value="0" required min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Acquisition Price</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" placeholder="Acquisition Price" name="input-pAcq" id="input-pAcq" value="0" step="0.01" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Package Price</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" placeholder="Package Price" name="input-pPkg" id="input-pPkg" value="0" step="0.01" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Retail Price</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" placeholder="Retail Price" name="input-pRet" id="input-pRet" value="0" step="0.01" min="0">
                    </div>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Item</button>
          </div>
          {{ Form::close() }}
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Edit Item</h4>
          </div>
          {{
            Form::open(array(
              'route' => 'editItem',
              'class' => 'form-horizontal',
              'id'    => 'form-editItem'
            ))
          }}
          <input type="hidden" name="input-oldLot" id="input-oldLot">
          <input type="hidden" name="input-oldExp" id="input-oldExp">
          <div class="modal-body">
            <div class="form-group">
                    <label class="col-xs-5" for="">Product Code</label>
                    <div class="col-xs-7" id="pcode">
                        <input type="text" class="typeahead form-control" placeholder="Product code" name="input-pcode" id="input-pcode" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Item Name</label>
                    <div class="col-xs-7" id="pcode">
                        <input type="text" class="typeahead form-control" placeholder="Product Name" name="input-name" id="input-name" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Item Description</label>
                    <div class="col-xs-7" id="pcode">
                        <textarea type="text" class="typeahead form-control" name="input-desc" id="input-desc">Put item description here </textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Lot No:</label>
                    <div class="col-xs-7" id="pcode">
                        <input type="text" class="typeahead form-control" placeholder="Lot Number" name="input-lot" id="input-lot" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Expiry</label>
                    <div class="col-xs-7" id="pcode">
                        <input type="hidden" name="hidden-dateexp" id="hidden-dateExp">
                        <input type="date" class="typeahead expiry-picker form-control" placeholder="Expiry Date" name="input-exp" id="input-exp2" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Supplier</label>
                    <div class="col-xs-7">
                        <select name="input-supplier" class="form-control" id="input-from">
                            <optgroup label="Suppliers">
                                @foreach($suppliers as $s)
                                    <option value="{{$s -> code}}">{{$s -> name}}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Category</label>
                    <div class="col-xs-7">
                        <select name="input-cat" id="input-cat" class="form-control">
                            @foreach ($itemTypes as $type)
                              <option value="{{$type -> code}}" selected>{{$type -> name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Inventory Threshold</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" placeholder="Quantity" name="input-invThresh" id="input-invThresh" value="5" required min="1">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Package Quantity</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" placeholder="Quantity" name="input-qtyPkg" id="input-qtyPkg" value="0" required min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Retail Quantity</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" placeholder="Quantity" name="input-qtyRet" id="input-qtyRet" value="0" required min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Acquisition Price</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" placeholder="Acquisition Price" name="input-pAcq" id="input-pAcq" value="0" step="0.01" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Package Price</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" placeholder="Package Price" name="input-pPkg" id="input-pPkg" value="0" step="0.01" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Retail Price</label>
                    <div class="col-xs-7">
                        <input type="number" class="form-control" placeholder="Retail Price" name="input-pRet" id="input-pRet" value="0" step="0.01" min="0">
                    </div>
                </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Edit Item</button>
          </div>
          {{ Form::close() }}
        </div>
      </div>
    </div>

@stop

@section('assets')
  {{HTML::script('assets/js/itemscript.js')}}
@stop