<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('total_just_from_place')->default(false); // Need as Salisbury total is only calculated from the finishing place.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('series_types');
    }
}
