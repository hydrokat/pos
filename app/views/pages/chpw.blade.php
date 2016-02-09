@extends('layouts.master')

@section('content')
    
    <div class="row">
        <div class="col-xs-4">
        @if (Session::has('message'))
            <div class="alert alert-success" role="alert">
                {{Session::get('message')}}
            </div>
        @endif
        
        @if ($errors -> has())
        <div class="alert alert-danger" role="alert">
            @foreach($errors -> all() as $error)        
                {{$error}} <br />
            @endforeach
        </div>
        @endif
        <div class="col-xs-12">
            {{
                Form::open(array(
                            'route' => 'chpw',
                            'class' => 'form-horizontal'
                            ))
            }}
                <div class="form-group">
                    <label class="col-xs-5" for="">Username</label>
                    <div class="col-xs-7">
                        <input type="password" class="form-control" placeholder="Username" name="username">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">New Password</label>
                    <div class="col-xs-7">
                        <input type="password" class="form-control" placeholder="New Password" name="newPw">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Confirm Password</label>
                    <div class="col-xs-7">
                        <input type="password" class="form-control" placeholder="Confirm Password" name="newPw_confirmation">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-xs-4 center-block">
                        <button class="btn btn-primary btn-lg">Change Password</button>
                    </div>
                </div>
            {{ Form::close() }}
        </div>

@stop

@section('assets')

@stop