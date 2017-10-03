<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('isAdmin')->default('0');
			$table->string('name', 255);
			$table->string('email', 255)->unique();
			$table->string('password');
			$table->ipAddress('ipaddress');
			$table->string('address', 255);
			$table->string('town', 255);
			$table->softDeletes();
			$table->rememberToken('rememberToken');
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

