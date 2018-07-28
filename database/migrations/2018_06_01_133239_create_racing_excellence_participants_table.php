<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRacingExcellenceParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('racing_excellence_participants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('racing_excellence_id')->unsigned();
            $table->integer('division_id')->unsigned()->index();
            $table->integer('jockey_id')->unsigned()->nullable()->index();
            $table->integer('api_id')->unsigned()->nullalble();
            $table->integer('api_animal_id')->unsigned()->nullalble();
            $table->string('animal_name')->nullable();
            $table->string('name')->nullable(); // For jockeys not on the system.
            $table->integer('place')->nullable(); // What position they came in the race.
            $table->integer('place_points')->nullable();
            $table->boolean('completed_race')->default(true);
            $table->integer('presentation_points')->nullable();
            $table->integer('professionalism_points')->nullable();
            $table->integer('coursewalk_points')->nullable();
            $table->integer('riding_points')->nullable();
            $table->integer('total_points')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();

            // $table->foreign('division_id')->references('id')->on('racing_excellence_divisions');
            // $table->foreign('jockey_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('racing_excellence_participants');
    }
}
