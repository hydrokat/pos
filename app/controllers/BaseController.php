<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	public function __construct()
    {
		
    }

    public function fetchSettings() {
        $settings = [];

        $settings['branches'] = Branches::all();

        return $settings;
    }

	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	protected function eventLog($user, $action = "") {
		$user = is_null($user) ? Auth::user() -> name : $user;

		AppLog::insertLog($user, $action);
	}
}
