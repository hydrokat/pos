@extends('layouts.master')

@section('content')
    {{Debugbar::info($sales)}}

    <div class="row">
        <div id="table-report">
            <h2 class="sub-header">Invoices</h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>                            
                            <th>Datetime</th>
                            <th>Invoice Number</th>
                            <th>Cashier Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $s)
                            <tr>
                                <td>
                                {{date("F d, Y h:i:sA", strtotime($s -> datetime))}}</td>
                                <td>{{link_to('/invoice/' . $s->invoiceNumber, $s->invoiceNumber, array('target'=>'_blank'))}}</td>
                                <td>{{$s->cashierName}}</td>
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

    {{HTML::script('assets/js/report.js')}}

    {{HTML::style('assets/css/print.css')}}

@stop