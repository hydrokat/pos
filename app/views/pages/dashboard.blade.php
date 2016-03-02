@extends('layouts.master')

@section('content')
    {{ Debugbar::info($inventory) }}
    <h1 class="page-header">Dashboard</h1>
          <div class="row placeholders">
          <div class="col-sm-12">
            <div>
                <h4>Daily Retail Sales</h4>
                <canvas id="canvas" height="80px"></canvas>
              </div>
            </div>        
          </div>

        <div class="row">
          
          <h2 class="sub-header">Inventory Report</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Name</th>
                  <th>Packages Left</th>
                  <th>Retail Left</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($inventory as $item)
                <tr>
                  <td>{{$item -> p_code}}</td>
                  <td>{{$item -> name}}</td>
                  <td>{{$item -> packages}}</td>
                  <td>{{$item -> retail}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

@stop

@section('assets')

  {{HTML::script('assets/js/script.js')}}
  {{HTML::script('assets/js/Chart.js')}}

  {{ HTML::style('assets/css/style_login.css') }}

@stop