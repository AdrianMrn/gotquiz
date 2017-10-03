<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLinkGotCharactersGotHousesTable extends Migration {

	public function up()
	{
		Schema::create('link_got_characters_got_houses', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('character_id')->unsigned();
			$table->integer('house_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('link_got_characters_got_houses');
	}
}