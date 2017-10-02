<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAliasesTable extends Migration {

	public function up()
	{
		Schema::create('aliases', function(Blueprint $table) {
			$table->increments('id');
			$table->string('alias', 255)->nullable();
			$table->integer('character_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('aliases');
	}
}