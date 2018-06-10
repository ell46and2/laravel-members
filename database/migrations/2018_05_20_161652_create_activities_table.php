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
            $table->integer('coach_id')->unsigned()->index();

            // Does this need to be polymorphic for activity types, racing excellence, away days etc?
            // $table->integer('activity_type_id')->unsigned(); 
              
            $table->date('start')->index();
            $table->date('end')->index();
            $table->integer('duration'); // in minutes
            $table->string('location')->nullable();
            $table->timestamps();

            $table->foreign('coach_id')->references('id')->on('users');
            // $table->foreign('activity_type_id')->references('id')->on('activity_types');
            
            // Possibly add a group (boolean) field - to save querying number of jockeys.
            // group activity feedback field
            // 
            // Individual feedback needs to go on seperate table.
            
            // Location can be a dropdown or free text.
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
