@extends('layouts.master')

@section('content')
    {{Debugbar::info($sales)}}
    <div class="row">
        {{
            Form::open(array(
                        'route' => 'sales-report',
                        'method' => 'get',
                        'class' => 'form-horizontal'
                        ))
        }}
        
        <div class="form-group form-inline">
            <div class="input-group">
                <span class="input-group-addon">From</span>
                <input type="date" class="form-control" placeholder="Date from" name="from" id="dFrom" required>
                <span class="input-group-addon">To</span>
                <input type="date" class="form-control" placeholder="Date to" name="to" id="dTo" required>
                <span class="input-group-addon">Branch</span>
                <select name="branch" class="form-control" id="input-branch" required>
                    <option value="All" selected>All Branches</option>
                    @foreach($branches as $b)
                        @if($b -> name == Session::get('branch'))
                            <option value="{{ $b -> id }}" selected>{{ $b -> name }}</option>
                        @else
                            <option value="{{ $b -> id }}">{{ $b -> name }}</option>
                        @endif
                    @endforeach
              </select>
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">Go!</button>
                </span>
            </div>
        </div>
        {{Form::close()}}
    </div>

    <div class="row">
        <div id="table-report">
            <h2 class="sub-header">Sales Report</h2>
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
                            @if($dStart != $dEnd)
                            <th>Time Sold</th>
                            @endif
                            <th>Product</th>
                            <th>Sale Type</th>
                            <th>Quantity</th>
                            <th>Discount</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $s)

                            <tr>
                                @if($dStart != $dEnd)
                                    <td>{{date("F d, Y h:i:sA", strtotime($s -> datetime))}}</td>
                                @endif
                                <td>{{$s -> p_code}}</td>
                                @if($s -> type == 'ret')
                                    <td>Retail</td>
                                @else
                                    <td>Package</td>
                                @endif
                                <td>{{$s -> qty}}</td>
                                <td>{{$s -> discount}}</td>
                                <td>{{$s -> amount }}</td>
                            </tr>
                        @endforeach
                            <tr>
                                <td colspan="4"></td>
                                <td><strong>Total Discount: {{number_format($tDsc, 2, '.', ',')}}</strong> </td>
                                <td>
                                    <strong>Gross Amount: {{number_format($tAmt + $tDsc, 2, '.', ',')}}</strong> <br>
                                    <strong>Net Amount: {{number_format($tAmt, 2, '.', ',')}}</strong> <br>
                                </td>
                            </tr>
                  </tbody>
                </table>
            </div>
        </div>
        <button class="btn btn-default btn-xs" onclick="printReport()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>
    </div>

@stop

@section('assets')

    {{HTML::script('assets/js/report.js')}}

    {{HTML::style('assets/css/print.css')}}

@stop