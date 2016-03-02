<?php

class HomeController extends BaseController {

	public function showDashboard()
	{
		$inventory = Inventory::getInventory();

		return View::make('pages.dashboard') -> with('inventory', $inventory);
	}

}
