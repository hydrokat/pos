<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sales', function($tbl) {
			$tbl -> increments('id');
			$tbl -> string('p_code');
			$tbl -> integer('branchID');
			$tbl -> string('invoiceNumber');
			$tbl -> string('cashierName');
			$tbl -> string('type'); //retail or package
			$tbl -> integer('qty');
			$tbl -> string('amount');
			$tbl -> string('discount');
			$tbl -> dateTime('datetime');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sales');
	}

}
