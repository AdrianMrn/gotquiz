<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
			$table->string('email', 150)->unique();
			$table->string('password', 255);
			$table->ipAddress('ipaddress')->nullable()->default(null);
			$table->string('address', 255)->nullable()->default(null);
			$table->string('town', 255)->nullable()->default(null);
			$table->softDeletes();
			$table->rememberToken('rememberToken');
			$table->timestamps();
			$table->integer('isAdmin')->default('0');
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}