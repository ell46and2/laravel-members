<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRacingExcellenceDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('racing_excellence_divisions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('division_number')->unsigned(); // 1 for div1, 2 for div2
            $table->integer('racing_excellence_id')->unsigned()->index();
            $table->dateTime('posted_to_api')->nullable();
            $table->timestamps();

            // $table->foreign('racing_excellence_id')->references('id')->on('racing_excellences');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('racing_excellence_divisions');
    }
}
