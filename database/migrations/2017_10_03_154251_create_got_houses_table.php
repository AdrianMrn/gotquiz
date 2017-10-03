<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGotHousesTable extends Migration {

	public function up()
	{
		Schema::create('got_houses', function(Blueprint $table) {
			$table->integer('id')->unique()->unsigned();
			$table->string('name', 255)->nullable();
			$table->string('region', 255)->nullable();
			$table->string('coatOfArms', 255)->nullable();
			$table->string('words', 255)->nullable();
			$table->integer('heir')->unsigned()->nullable();
			$table->integer('founder')->unsigned()->nullable();
			$table->integer('currentLord')->unsigned()->nullable();
		});
	}

	public function down()
	{
		Schema::drop('got_houses');
	}
}