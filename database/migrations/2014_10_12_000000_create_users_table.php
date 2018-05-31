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
            $table->integer('role_id')->unsigned()->default(1);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('telephone');
            $table->string('street_address');
            $table->string('city');
            $table->string('postcode');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('approved')->default(false);
            $table->rememberToken();
            $table->timestamps();

            // Need to add fields: 
            // - last_login
            // - middle_name (nullable)
            // - DOB
            // County
            // Country
            // Telephone
            // Twitter handle
            // Known as (alias)
            // Sex
            // Jockey status ? - for jockeys that vacate from the system for a period of time.

            $table->foreign('role_id')->references('id')->on('roles');
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
