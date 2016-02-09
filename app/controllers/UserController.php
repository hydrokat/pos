<?php

class UserController extends BaseController {
    public $restful = true;

    public function login(){
        $validator = Validator::make( Input::all(),
            array(
                'username' => 'required',
                'password' => 'required'
            )
        );

        if ($validator->fails()) {
            return Redirect::route('login')
                    -> withErrors($validator)
                    -> withInput();
        } else {
            $usr = array(
                    'username' => Input::get('username'),
                    'password'  => Input::get('password'),
                );

            $auth = false;
            $auth = Input::get('remember') ? Auth::attempt($usr, true) : Auth::attempt($usr);

            if($auth) {
                $isExpired = User::isExpired($usr['username']);
                if($isExpired){
                    Auth::logout();
                    return Redirect::to('/login-page') -> with('message', 'Login Failed. This account has expired.');
                } else {
                    Session::put('uname', $usr['username']);

                    $this -> eventLog($usr['username'] ,"User has logged in.");

                    return Redirect::intended('dashboard');
                }
            } else {
                return Redirect::to('/login-page') -> with('message', 'Login Failed. Account credentials invalid.');
            }
        }
    }

    public function chpw(){
        $messages = array(
            'newPw.required'  => 'Please input new password.',
            'confirmed'  => 'Password not confirmed properly.'
        );

        $val = Validator::make( Input::all(),
            array(
                'newPw'    => 'required|confirmed|min:8'
            , $messages)
        );

        if ($val->fails()) {
            return Redirect::to('/acct/chpw')
                    -> withErrors($val)
                    -> withInput();
        } else {
            $usr = array(
                    'new_password'  => Input::get('newPw'),
                    'username'  => Session::get('uname'),
                );

            $thisUser = User::where('username', '=', Input::get('username')) -> where('role', '=>', Auth::user()->role) -> first();

            if(Hash::check($usr['password'], $thisUser -> getAuthPassword())) {
                $msg = "";
                $err = array('Password is incorrect');
            } else {
                $msg = "Your password has been changed.";
                $err = array();

                $thisUser -> password = Hash::make($usr['new_password']);

                $thisUser -> save();
            }

            return Redirect::to('/acct/chpw')
                        -> with('message', $msg)
                        -> withErrors($err);
        }
    }

    public function getCreate(){
        if(Auth::user()->role > 2){
            return Redirect::to('/dashboard');
        } else {
            return View::make('pages.createAcct');
        }
    }

    public function postCreate(){
        $messages = array(
            'pw.required'   => 'Password is required.',
            'confirmed'     => 'Password did not match.',
            'exp.required'  => 'An expiry date is required.',
        );

        $val = Validator::make( Input::all(),
            array(
                'username'  => 'required|unique:users',
                'pw'        => 'required|confirmed|min:8',
                'name'      => 'required',
                'exp'       => 'required'
            , $messages)
        );

        if(!$val->fails()){
            $newUser = new User;

            $newUser -> username = Input::get('username');
            $newUser -> password = Hash::make(Input::get('pw'));
            $newUser -> name     = Input::get('name');
            $newUser -> expiry   = Input::get('exp');
            $newUser -> role     = Input::get('role');

            $newUser -> save();

            return View::make('pages.createAcct') -> with('message', "Account created!");            
        } else {
            return Redirect::route('create-account')
                            -> withInput()
                            -> withErrors($val);
        }
    }
}