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
			$table->datetime('start');
			$table->datetime('end');
			$table->string('status')->default('upcoming'); //values are upcoming, running & finished
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('contests');
	}
}