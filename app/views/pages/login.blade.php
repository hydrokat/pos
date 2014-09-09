@extends('layouts.guest')

@section('content')

    <form class="form-signin" role="form">
        <h2 class="form-signin-heading">POS System Sign In</h2>
        <input type="text" class="form-control" placeholder="Username" required autofocus>
        <input type="password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>

@stop

@section('assets')
    
    {{HTML::style('assets/css/style_login.css')}}

@stop