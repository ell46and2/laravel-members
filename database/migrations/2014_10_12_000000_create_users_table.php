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
            $table->string('middle_name')->nullable();
            $table->string('alias')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->string('county');
            $table->string('country');
            $table->string('postcode');
            $table->string('telephone');
            $table->string('twitter_handle')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('approved')->default(false);
            $table->string('avatar_filename')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Need to add fields: 
            // - last_login
            // Jockey status ? - for jockeys that vacate from the system for a period of time.
            // profile_completion % - when profile is updated (and jockey registers) update. Probably use an observer to calculate.

            // $table->foreign('role_id')->references('id')->on('roles');
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
