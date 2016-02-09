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
                            <th>Packages</th>
                            <th>Retail</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inv as $i)
                                <td>{{$i -> p_code}}</td>
                                <td>{{$i -> lotNo}}</td>
                                <td>{{$i -> expiry}}</td>
                                <td>{{$i -> name}}</td>
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