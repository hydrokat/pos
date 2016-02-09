<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transfers', function($tbl) {
			$tbl -> increments('id');
			$tbl -> string('invoiceNumber');
			$tbl -> string('cashierName');
			$tbl -> datetime('datetime');
			$tbl -> boolean('inOrOut');
			$tbl -> string('from');
			$tbl -> string('to');
			$tbl -> string('p_code');
			$tbl -> string('lotNo');
			$tbl -> date('expiry');
			$tbl -> string('type');
			$tbl -> integer('quantity');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('transfers');
	}

}
