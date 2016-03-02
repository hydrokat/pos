@extends('layouts.master')

@section('content')
    
    <div class="row">

        <div class="col-sm-6">
            <div>
                <h4>Today's Sales</h4>
                <canvas id="canvas"></canvas>
              </div>
            </div>

            <div class="col-sm-6">
            <h4>Top Selling</h4>
              <div class="row">              
                <div class="col-sm-9">
                  <canvas id="canvas2"></canvas>
                </div>
                <div class="col-sm-3">
                  <div id="legend"></div>
                </div>
              </div>
            </div>
        
          </div>

    </div>

@stop

@section('assets')

    {{HTML::script('assets/js/Chart.js')}}
    {{HTML::script('assets/js/report.js')}}

    {{HTML::style('assets/css/style_login.css')}}

@stop