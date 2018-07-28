<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityJockeyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_jockey', function (Blueprint $table) {
            $table->integer('activity_id')->unsigned();
            $table->integer('jockey_id')->unsigned();
            $table->string('feedback')->nullable();
            $table->timestamps();

            // Do we add individual coach feedback here?

            // $table->foreign('activity_id')->references('id')->on('activities');
            // $table->foreign('jockey_id')->references('id')->on('users');

            $table->primary(array('activity_id', 'jockey_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_jockey');
    }
}
