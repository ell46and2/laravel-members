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
            $table->integer('activity_type_id')->unsigned();
            $table->integer('coach_id')->unsigned()->index();
            $table->dateTime('start')->index();
            $table->dateTime('end')->index()->nullable();
            $table->integer('duration')->nullable(); // in minutes
            $table->integer('location_id')->unsigned()->nullable(); // location either dropdown id or free test.
            $table->string('location_name')->nullable();
            $table->boolean('group')->default(false);
            $table->string('group_feedback')->nullable();
            $table->timestamps();

            $table->foreign('coach_id')->references('id')->on('users');
            // $table->foreign('activity_type_id')->references('id')->on('activity_types');
            
            // Possibly add a group (boolean) field - to save querying number of jockeys.
            // group activity feedback field
            // 
            // Individual feedback needs to go on seperate table.
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
