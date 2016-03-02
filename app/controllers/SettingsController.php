<?php

class SettingsController extends BaseController {

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */

    public function getSettings() {
        $settings = $this -> fetchSettings();
        return View::make('pages.settings') -> with('branches', $settings['branches']);
    }

    public function setBranch() {
        $branch = Branches::find(Input::get('branch'));
        Session::put('branch', $branch -> name);
        Session::put('branchid', Input::get('branch'));

        $msg = "Branch has been updated to " . $branch -> name . "!";

        $this -> eventLog(Auth::user() -> name, $msg);

        $settings = $this -> fetchSettings();
        return Redirect::route('get-settings') -> with('branches', $settings['branches'])
                                            -> with('message', $msg);
    }

    public function addBranch() {
        $settings = $this -> fetchSettings();

        $validator = Validator::make( Input::all(),
            array(
                'branch'  => 'required|unique:branches,name',
                'address'   => 'required'
            )
        );

        if($validator -> fails()){
            return Redirect::route('get-settings') -> with('branches', $settings['branches'])
                                                   -> withErrors($validator)
                                                   -> withInput();
        } else {
            $b = new Branches;
            $b -> name = Input::get('branch');
            $b -> address = Input::get('address');
            $b -> save();

            $msg = "Branch has been added!";
            $this -> eventLog(Auth::user() -> name, $msg);

            return Redirect::route('get-settings') -> with('branches', $settings['branches'])
                                                   -> with('message', $msg);   
        }
    }

    public function addSupplier() {
        $settings = $this -> fetchSettings();

        $validator = Validator::make( Input::all(),
            array(
                'code'  => 'required|unique:suppliers,code',
                'name'   => 'required'
            )
        );

        if($validator -> fails()){
            return Redirect::route('get-settings') -> with('branches', $settings['branches'])
                                                   -> withErrors($validator)
                                                   -> withInput();
        } else {
            $s = new Supplier;
            $s -> code = Input::get('code');
            $s -> name = Input::get('name');
            $s -> save();

            $msg = Input::get('name') . " has been added as a supplier with the code, " . Input::get('code');

            $this -> eventLog(Auth::user() -> name, $msg);

            return Redirect::route('get-settings') -> with('branches', $settings['branches'])
                                                   -> with('message', $msg);   
        }
    }
}
