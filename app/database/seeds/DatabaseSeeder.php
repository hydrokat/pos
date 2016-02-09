<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('UserSeeder');
		//$this->call('ItemInventorySeeder');
		$this->call('DiscountSeeder');
		$this->call('BranchSeeder');
		$this->call('SupplierSeeder');
		$this->call('ItemTypeSeeder');
	}

}
