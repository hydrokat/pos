<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($tbl) {
			$tbl -> increments('id');
			$tbl -> string('username');
			$tbl -> string('password');
			$tbl -> string('name');
			$tbl -> integer('role'); //1-admin, 2-owner, 3-emp
			$tbl -> string('remember_token');
			$tbl -> date('expiry') -> nullable();
			$tbl -> timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}

}
