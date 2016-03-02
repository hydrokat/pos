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
                            'route' => 'post-create-account',
                            'class' => 'form-horizontal'
                            ))
            }}
                <div class="form-group">
                    <label class="col-xs-5" for="">Username</label>
                    <div class="col-xs-7">
                        <input type="text" class="form-control" placeholder="Username" name="username">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Name</label>
                    <div class="col-xs-7">
                        <input type="text" class="form-control" placeholder="Name" name="name">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Password</label>
                    <div class="col-xs-7">
                        <input type="password" class="form-control" placeholder="Enter Password" name="pw">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Confirm Password</label>
                    <div class="col-xs-7">
                        <input type="password" class="form-control" placeholder="Confirm Password" name="pw_confirmation">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Role</label>
                    <div class="col-xs-7">
                        <select name="role" id="" class="form-control">
                            @if(Auth::user()->role == 1)
                                <option value="2">Owner</option>
                                <option value="3">Employee</option>
                            @else
                                <option value="3">Employee</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Account Expiry</label>
                    <div class="col-xs-7">
                        <input type="date" class="form-control datepicker" placeholder="Expiry" name="exp" id="" required>
                    </div>
                </div>                
                
                <div class="form-group">
                    <div class="col-xs-4 center-block">
                        <button class="btn btn-primary btn-lg">Create Account</button>
                    </div>
                </div>
            {{ Form::close() }}
        </div>

@stop

@section('assets')

@stop