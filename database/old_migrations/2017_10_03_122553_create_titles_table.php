<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTitlesTable extends Migration {

	public function up()
	{
		Schema::create('titles', function(Blueprint $table) {
			$table->increments('id');
			$table->string('title', 255)->nullable();
			$table->integer('character_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('titles');
	}
}