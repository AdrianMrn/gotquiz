<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGotCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('got_characters', function (Blueprint $table) {
            $table->primary('char_url')->unique();
            $table->string('name')->nullable();
            $table->string('culture')->nullable();
            $table->string('titles')->nullable();
            $table->string('aliases')->nullable();
            $table->string('father')->nullable();
            $table->string('mother')->nullable();
            $table->string('spouse')->nullable();
            $table->string('playedBy')->nullable();

        });
    } 

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('got_characters');
    }
}
