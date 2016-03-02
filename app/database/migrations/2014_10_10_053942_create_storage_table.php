<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('storage', function($tbl) {
			$tbl -> increments('id');
			$tbl -> integer('itemId');
			$tbl -> string('room');
			$tbl -> string('shelf');
			$tbl -> string('cabinet');
			$tbl -> string('username');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('storage');
	}

}
