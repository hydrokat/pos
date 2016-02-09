@extends('layouts.invoice')

@section('content')

    {{Debugbar::info($sales)}}

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
          <h1 id="invoice-type">SALE INVOICE</h1>
          <h1 id="invoice-info"><small>{{$invoiceNumber}} ({{date("F d, Y", strtotime($date))}})</small><br><small>Cashier: {{$cashier}}</small></h1>
        </div>
      </div>
      <div id="invoice-data">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>
                <h4>Product Code</h4>
              </th>
              <th>
                <h4>Quantity</h4>
              </th>
              <th>
                <h4>Amount</h4>
              </th>
              <th>
                <h4>Discount</h4>
              </th>
              <th>
                <h4>Total</h4>
              </th>
            </tr>
          </thead>
          <tbody>
              @foreach($sales as $s)
                  <tr>
                      <td>{{$s->p_code}}</td>
                      <td>{{$s->qty}}</td>
                      <td>{{$s->amount + $s->discount}}</td>
                      <td>{{$s->discount}}</td>
                      <td>{{$s->amount}}</td>
                  </tr>
              @endforeach
          </tbody>
        </table>
        <div class="row text-right">
          <div class="col-xs-2 col-xs-offset-8">
            <p>
              <strong>
              Sub Total : <br>
              Discount : <br>
              Total : <br>
              </strong>
            </p>
          </div>
          <div class="col-xs-2">
            <strong>
            {{$tAmt}} <br>
            {{$tDsc}} <br>
            {{$tAmt}} <br>
            </strong>
          </div>
        </div>
      </div>
      <div class="col-xs-12">
        <p>Thank you! Have a nice day!</p>
      </div>
    </div>

@stop

@section('assets')

{{HTML::style('assets/css/invoice.css')}}
  
@stop