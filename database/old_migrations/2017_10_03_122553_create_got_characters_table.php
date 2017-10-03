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
			$table->string('father', 255)->nullable();
			$table->string('mother', 255)->nullable();
			$table->string('spouse', 255)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('got_characters');
	}
}