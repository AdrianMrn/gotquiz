<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContestsTable extends Migration {

	public function up()
	{
		Schema::create('contests', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('winner_id')->unsigned()->nullable();
		});
	}

	public function down()
	{
		Schema::drop('contests');
	}
}