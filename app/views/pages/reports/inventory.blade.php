@extends('layouts.master')

@section('content')
    {{Debugbar::info($inv)}}
    <div class="row">
        {{
            Form::open(array(
                        'route' => 'ending-report',
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
    </div>

    <div class="row">
        <div id="table-report">
            <h2 class="sub-header">Inventory Report</h2>
            <h5 class="sub-header">
            @if(isset($dStart) && isset($dEnd))
                from
                {{date("F j, Y", strtotime($dStart))}}
                to
                {{date("F j, Y", strtotime($dEnd))}}
            @else
                for
                {{date("F j, Y")}}
            @endif
            </h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Lot</th>
                            <th>Expiry</th>
                            <th>Name</th>
                            <th>Acquisition Price</th>
                            <th>Packages</th>
                            <th>Retail</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inv as $i)
                            @if($i -> retail <= $i -> inventory_threshold)
                                <tr class="criticalStock">                  
                            @elseif(strtotime($i -> expiry) - time()-(60*60*24) <= 190 * 24 * 60 * 60)
                                <tr class="expiring">
                            @else
                                <tr>
                            @endif
                                <td>{{$i -> p_code}}</td>
                                <td>{{$i -> lotNo}}</td>
                                <td>{{$i -> expiry}}</td>
                                <td>{{$i -> name}}</td>
                                <td>{{$i -> acquisition_price}}</td>
                                <td>{{$i -> packages}}</td>
                                <td>{{$i -> retail}}</td>
                                <td>{{date("F d, Y h:i:sA", strtotime($i -> updated_at))}}</td>
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
    {{HTML::script('assets/js/invScript.js')}}

    {{HTML::style('assets/css/print.css')}}
@stop