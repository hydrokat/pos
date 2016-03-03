@extends('layouts.guest')

@section('content')
    {{ Form::open(array('route' => 'login', 'class' => 'form-signin', 'role' => 'form')) }}
        <h2 class="form-signin-heading">POS System Sign In</h2>
        @if (Session::has('message'))
            <div class="alert alert-danger" role="alert">{{ Session::get('message') }}</div>
        @endif
        <input type="text" class="form-control" placeholder="Username" name="username" required autofocus>
        <input type="password" class="form-control" placeholder="Password" name="password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <div class="checkbox">
        <label>
          <input type="checkbox" name="remember"> Remember me
        </label>
      </div>
      <a href="#" onclick="alert('Dedicated to Thea. <3')"><3</a>
    {{ Form::close() }}

@stop

@section('assets')

    {{HTML::style('assets/css/style_login.css',[], !App::isLocal())}}

@stop
