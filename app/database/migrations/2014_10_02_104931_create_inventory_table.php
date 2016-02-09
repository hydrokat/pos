<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventory', function($tbl) {
			$tbl -> increments('id');
			$tbl -> string('p_code');
			$tbl -> string('lotNo') -> default(00000);
			$tbl -> date('expiry') -> default('2030-01-01');
			$tbl -> integer('branchID');
			$tbl -> string('supplier')->nullable();
			$tbl -> double('acquisition_price')->nullable();
			$tbl -> integer('packages') -> default(0); //boxes
			$tbl -> integer('retail') -> default(0); //per piece
			$tbl -> timestamps();
			$tbl -> unique(array('p_code', 'lotNo' ,'expiry', 'branchID'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('inventory');
	}

}
