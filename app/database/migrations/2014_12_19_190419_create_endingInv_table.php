<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEndingInvTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('endingInv', function($tbl) {
			$tbl -> increments('id');
			$tbl -> string('p_code', 20);
			$tbl -> string('lotNo', 20);
			$tbl -> date('expiry');
			$tbl -> integer('branchID');
			$tbl -> date('date');
			$tbl -> integer('retail');
			$tbl -> integer('packages');

			$tbl -> unique(array('p_code', 'lotNo', 'expiry', 'branchID' ,'date'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('endingInv');
	}

}
