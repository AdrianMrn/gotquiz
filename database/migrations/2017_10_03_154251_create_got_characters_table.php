<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGotCharactersTable extends Migration {

	public function up()
	{
		Schema::create('got_characters', function(Blueprint $table) {
			$table->integer('id')->unique()->unsigned();
			$table->string('name', 255)->nullable();
			$table->string('culture', 255)->nullable();
			$table->integer('father')->unsigned()->nullable();
			$table->integer('mother')->unsigned()->nullable();
			$table->integer('spouse')->unsigned()->nullable();
		});
	}

	public function down()
	{
		Schema::drop('got_characters');
	}
}