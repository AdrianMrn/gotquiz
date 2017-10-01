<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGotHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('got_houses', function (Blueprint $table) {
            $table->primary('house_url')->unique();
            $table->string('name')->nullable();
            $table->string('region')->nullable();
            $table->string('coatOfArms')->nullable();
            $table->string('words')->nullable();
            $table->string('titles')->nullable();
            $table->string('seats')->nullable();
            $table->string('ancestralWeapons')->nullable();
            $table->string('cadetBranches')->nullable();
            $table->string('')->nullable();
            
            $table->string('currentLord')->nullable();
            $table->string('heir')->nullable();
            $table->string('overlord')->nullable();
            $table->string('founder')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('got_houses');
    }
}
