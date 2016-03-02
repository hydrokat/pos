@extends('layouts.master')

@section('content')
    {{Debugbar::info($logs)}}
    <div class="row">
        {{
            Form::open(array(
                        'route' => 'post-logs',
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
            <h2 class="sub-header">Logs</h2>
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
                            <th>Username</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $l)
                                <td>{{date("F d, Y h:i:sA", strtotime($l -> datetime))}}</td>
                                <td>{{$l -> username}}</td>
                                <td>{{$l -> action}}</td>
                            </tr>
                        @endforeach
                  </tbody>
                </table>
            </div>
        </div>
        <button class="btn btn-default btn-xs" onclick="printReport()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>
    </div>

@stop

@section('assets')
    {{HTML::script('assets/js/adminscript.js')}}

    {{HTML::style('assets/css/print.css')}}
@stop