<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRacingExcellencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('racing_excellences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coach_id')->unsigned();
            $table->string('location')->nullable();
            $table->timestamp('start');
            // $table->timestamp('end');
            // $table->integer('duration'); // in minutes
            // $table->
            $table->timestamps();

            $table->foreign('coach_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('racing_excellences');
    }
}
