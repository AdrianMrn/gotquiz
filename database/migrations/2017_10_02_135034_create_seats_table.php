<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeatsTable extends Migration {

	public function up()
	{
		Schema::create('seats', function(Blueprint $table) {
			$table->increments('id');
			$table->string('seat', 255)->nullable();
			$table->integer('house_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('seats');
	}
}