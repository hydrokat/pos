@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-xs-12">
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
        </div>

        <div class="row">
            <div class="col-xs-4">
                <h3>
                    Expiry date: {{Auth::user() -> expiry}}
                </h3>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 rounded-box">
                <h4>Set your branch here:</h4>
                {{
                    Form::open(array(
                            'route' => 'set-branch',
                            'class' => 'form-horizontal'
                            ))
                }}
                <div class="form-group form-inline">
                    <div class="input-group">
                      <span class="input-group-addon">Branch</span>
                      <select name="branch" class="form-control" id="input-branch" required>
                        @foreach($branches as $b)
                            @if($b -> name == Session::get('branch'))
                                <option value="{{ $b -> id }}" selected>{{ $b -> name }}</option>
                            @else
                                <option value="{{ $b -> id }}">{{ $b -> name }}</option>
                            @endif
                        @endforeach
                      </select>
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">Set!</button>
                      </span>
                    </div>
                </div>

            {{ Form::close() }}
            </div>
        </div>
        <div class="row hidden">
            <div class="col-xs-12 rounded-box">
                <h4>Add a Branch</h4>
                {{
                    Form::open(array(
                            'route' => 'post-addBranch',
                            'class' => 'form-horizontal'
                            ))
                }}
                <div class="form-group">
                    <label class="col-xs-5" for="">Branch Name</label>
                    <div class="col-xs-7">
                        <input type="text" class="form-control" placeholder="Branch Name" name="branch" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-5" for="">Branch Address</label>
                    <div class="col-xs-7">
                        <input type="text" class="form-control" placeholder="Address" name="address" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-4 center-block">
                        <button class="btn btn-primary btn-md">Add</button>
                    </div>
                </div>

            {{ Form::close() }}
            </div>
        </div>
        @if(Auth::user()->role <= 2)
            <div class="row">
                <div class="col-xs-12 rounded-box">
                    <h4>Add a Supplier</h4>
                    {{
                        Form::open(array(
                                'route' => 'post-addSupplier',
                                'class' => 'form-horizontal'
                                ))
                    }}
                    <div class="form-group">
                        <label class="col-xs-5" for="">Supplier Code</label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" placeholder="Supplier Code" name="code" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-5" for="">Branch Address</label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" placeholder="Name" name="name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-4 center-block">
                            <button class="btn btn-primary btn-md">Add</button>
                        </div>
                    </div>

                {{ Form::close() }}
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-xs-12">
                {{ link_to('/acct/chpw', 'Change Your Password Here') }}
            </div>
        </div>
    </div>

@stop

@section('assets')

@stop