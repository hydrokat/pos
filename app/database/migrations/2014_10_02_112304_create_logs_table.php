<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('logs', function($tbl) {
			$tbl -> increments('id');
			$tbl -> text('action'); //description of action
			$tbl -> string('username'); //username
			$tbl -> string('branch'); //branch
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
		Schema::dropIfExists('logs');
	}

}
