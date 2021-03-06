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
			$table->integer('winner_points')->default(0);
			$table->integer('contest_admin_id')->unsigned()->nullable();
			$table->integer('participations_allowed_daily')->default(5);
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