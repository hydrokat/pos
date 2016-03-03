<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('items', function($tbl) {
			$tbl -> increments('id');
			$tbl -> string('p_code', 20);
			$tbl -> string('name') -> default("ProductName");
			$tbl -> string('desc') -> nullable();
			$tbl -> string('size') -> nullable();
			$tbl -> string('category') -> default('med'); //csm,med,msp,lab,labsup,meq
			$tbl -> string('lotNo', 20) -> default(00000);
			$tbl -> date('expiry') -> default('2030-01-01');
			$tbl -> integer('inventory_threshold') -> default(5);
			$tbl -> double('price_retail') -> default(0);
			$tbl -> double('price_package') -> default(0);
			$tbl -> integer('branchID');
			$tbl -> timestamps();
			$tbl -> softDeletes();
			$tbl -> unique(array('p_code', 'expiry' ,'lotNo'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('items');
	}

}
