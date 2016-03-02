@extends('layouts.master')

@section('content')
    {{Debugbar::info($transfers)}}
    <div class="row">
        {{
            Form::open(array(
                        'route' => 'trans-report',
                        'method' => 'get',
                        'class' => 'form-horizontal'
                        ))
        }}
        
        <div class="form-group form-inline">
            <div class="input-group">
              <span class="input-group-addon">From</span>
              <input type="date" class="form-control" placeholder="Date from" name="from" id="dFrom">
              <span class="input-group-addon">To</span>
              <input type="date" class="form-control" placeholder="Date to" name="to" id="dTo">
              <span class="input-group-btn">
                <button class="btn btn-default" type="submit">Go!</button>
              </span>
            </div>
        </div>
        {{Form::close()}}
    </div>
    <div class="row">
      <div class="col-xs-6">
        <button type="button" id="btn-itemFilters" class="btn btn-default btn-sm">
          <span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filters
        </button>
        <ul class="item-filters">
          <li><a href="#" data-sort="all" id="filter-stock">All</a></li>
          <li><a href="#" data-sort="in" id="filter-stock">In</a></li>
          <li><a href="#" data-sort="out" id="filter-exp">Out</a></li>
        </ul>
      </div>
    </div>

    <div class="row">
        <div id="table-report">
            <h2 class="sub-header">Transfers Report</h2>
            <h5 class="sub-header">
            @if(isset($dStart) && isset($dEnd))
                from
                {{date("F j, Y", strtotime($dStart))}}
                to
                {{date("F j, Y", strtotime($dEnd))}}
            @endif
            </h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Datetime</th>
                            <th>Invoice #</th>
                            <th>In Or Out</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Product Code</th>
                            <th>Type</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transfers as $t)
                                <td>{{date("F d, Y h:i:sA", strtotime($t -> datetime))}}</td>
                                <td>{{link_to('/dinvoice/' . $t->invoiceNumber, $t->invoiceNumber, array('target'=>'_blank'))}}</td>
                                
                                @if($t -> inOrOut == 1)
                                    <td>In</td>
                                @else
                                    <td>Out</td>
                                @endif
                                
                                <td>{{$t -> from}}</td>
                                <td>{{$t -> to}}</td>
                                <td>{{$t -> p_code}}</td>

                                @if($t -> type == 'ret')
                                    <td>Retail</td>
                                @else
                                    <td>Package</td>
                                @endif

                                <td>{{$t -> quantity}}</td>
                            </tr>
                        @endforeach
                  </tbody>
                </table>
            </div>
        </div>
        <button class="btn btn-default btn-xs" onclick="printReport()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>
    </div>

@stop

@section('assets')
    {{HTML::script('assets/js/transfersReport.js')}}

    {{HTML::style('assets/css/print.css')}}
@stop