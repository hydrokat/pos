@extends('layouts.invoice')

@section('content')

    {{Debugbar::info($transfers)}}

    <div class="container">
      <div class="row">
        <div class="col-xs-6">
          <h3 id="logo">
            <a href="#">
            <img src="{{asset('assets/images/medpro1.png')}}" width="80px" style="float:left">
            Med Pro Medical <br> Supplies and Equipment
            </a>
          </h3>
        </div>
        <div class="col-xs-6 text-right">
          <h0 id="invoice-type">DELIVERY INVOICE</h1>
          <h1 id="invoice-info"><small>{{$invoiceNumber}} ({{date("F d, Y", strtotime($date))}})</small><br><small>Cashier: {{$cashier}}</small></h1>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-5">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4>From: {{$from}}</h4>
            </div>
          </div>
        </div>
        <div class="col-xs-5 col-xs-offset-2 text-right">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4>To : {{$to}}</h4>
            </div>
          </div>
        </div>
      </div>
      <!-- / end client details section -->
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>
              <h4>Datetime</h4>
            </th>
            <th>
              <h4>Product Code</h4>
            </th>
            <th>
              <h4>Lot#</h4>
            </th>
            <th>
              <h4>Expiry</h4>
            </th>            
            <th>
              <h4>Type</h4>
            </th>
            <th>
              <h4>Quantity</h4>
            </th>
          </tr>
        </thead>
        <tbody>
            @foreach($transfers as $t)
                <tr>
                    <td>{{date("F d, Y H:i:s", strtotime($t->datetime))}}</td>
                    <td>{{$t->p_code}}</td>
                    <td>{{$t->lotNo}}</td>
                    <td>{{$t->expiry}}</td>
                    <td>{{$t->type}}</td>
                    <td>{{$t->quantity}}</td>
                </tr>
            @endforeach
        </tbody>
      </table>
    </div>

@stop

@section('assets')

{{HTML::style('assets/css/invoice.css')}}

@stop