<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coach_id')->unsigned();

            // Does this need to be polymorphic for activity types, racing excellence, away days etc?
            // $table->integer('activity_type_id')->unsigned(); 
              
            $table->date('start');
            $table->date('end');
            $table->integer('duration'); // in minutes
            $table->string('location')->nullable();
            $table->timestamps();

            $table->foreign('coach_id')->references('id')->on('users');
            // $table->foreign('activity_type_id')->references('id')->on('activity_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
