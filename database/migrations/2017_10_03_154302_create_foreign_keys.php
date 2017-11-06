<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('got_characters', function(Blueprint $table) {
			$table->foreign('father')->references('id')->on('got_characters')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('got_characters', function(Blueprint $table) {
			$table->foreign('mother')->references('id')->on('got_characters')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('got_characters', function(Blueprint $table) {
			$table->foreign('spouse')->references('id')->on('got_characters')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('got_houses', function(Blueprint $table) {
			$table->foreign('heir')->references('id')->on('got_characters')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('got_houses', function(Blueprint $table) {
			$table->foreign('founder')->references('id')->on('got_characters')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('got_houses', function(Blueprint $table) {
			$table->foreign('currentLord')->references('id')->on('got_characters')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('titles', function(Blueprint $table) {
			$table->foreign('character_id')->references('id')->on('got_characters')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('seats', function(Blueprint $table) {
			$table->foreign('house_id')->references('id')->on('got_houses')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('aliases', function(Blueprint $table) {
			$table->foreign('character_id')->references('id')->on('got_characters')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('link_got_characters_got_houses', function(Blueprint $table) {
			$table->foreign('character_id')->references('id')->on('got_characters')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('link_got_characters_got_houses', function(Blueprint $table) {
			$table->foreign('house_id')->references('id')->on('got_houses')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->foreign('contest_id')->references('id')->on('contests')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('contests', function(Blueprint $table) {
			$table->foreign('winner_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('contests', function(Blueprint $table) {
			$table->foreign('contest_admin_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('got_characters', function(Blueprint $table) {
			$table->dropForeign('got_characters_father_foreign');
		});
		Schema::table('got_characters', function(Blueprint $table) {
			$table->dropForeign('got_characters_mother_foreign');
		});
		Schema::table('got_characters', function(Blueprint $table) {
			$table->dropForeign('got_characters_spouse_foreign');
		});
		Schema::table('got_houses', function(Blueprint $table) {
			$table->dropForeign('got_houses_heir_foreign');
		});
		Schema::table('got_houses', function(Blueprint $table) {
			$table->dropForeign('got_houses_founder_foreign');
		});
		Schema::table('got_houses', function(Blueprint $table) {
			$table->dropForeign('got_houses_currentLord_foreign');
		});
		Schema::table('titles', function(Blueprint $table) {
			$table->dropForeign('titles_character_id_foreign');
		});
		Schema::table('seats', function(Blueprint $table) {
			$table->dropForeign('seats_house_id_foreign');
		});
		Schema::table('aliases', function(Blueprint $table) {
			$table->dropForeign('aliases_character_id_foreign');
		});
		Schema::table('link_got_characters_got_houses', function(Blueprint $table) {
			$table->dropForeign('link_got_characters_got_houses_character_id_foreign');
		});
		Schema::table('link_got_characters_got_houses', function(Blueprint $table) {
			$table->dropForeign('link_got_characters_got_houses_house_id_foreign');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->dropForeign('participations_user_id_foreign');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->dropForeign('participations_contest_id_foreign');
		});
		Schema::table('contests', function(Blueprint $table) {
			$table->dropForeign('contests_winner_id_foreign');
		});
		Schema::table('contests', function(Blueprint $table) {
			$table->dropForeign('contest_admin_id_foreign');
		});
	}
}