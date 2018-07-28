<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesScoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series_scorings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('series_type_id')->unsigned()->index();
            $table->integer('place')->unsigned();
            $table->integer('points')->unsigned();

            // $table->foreign('series_type_id')->references('id')->on('series_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('series_scorings');
    }
}
