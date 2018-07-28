<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoachJockeyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coach_jockey', function (Blueprint $table) {
            $table->integer('coach_id')->unsigned();
            $table->integer('jockey_id')->unsigned();

            // $table->foreign('coach_id')->references('id')->on('users');
            // $table->foreign('jockey_id')->references('id')->on('users');

            $table->primary(array('coach_id', 'jockey_id'));

            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coach_jockey');
    }
}
