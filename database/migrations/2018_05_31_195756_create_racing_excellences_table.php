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
            $table->integer('coach_id')->unsigned()->index();
            $table->integer('location_id')->unsigned();
            $table->integer('series_id')->unsigned();
            $table->boolean('completed')->default(false);
            $table->timestamp('start');
            $table->timestamps();

            $table->foreign('coach_id')->references('id')->on('users');

            // Has statuses of 'pending race', 'pending results', 'completed',
            // but will calculate them by:
            // 'pending race': - if 'start' is in future.
            // 'pending results': - if 'start' is past and the 'presentation_points' for first participant haven't been entered. (NOTE: do we need to check all participants).
            

            // OR
            // 'pending race': - if 'start' is in future.
            // we add a boolean field called 'completed' that gets set to true when the coach enters the race results. Maybe with observer.
            // 'pending results': - if 'start' is past and 'completed' = false
            // 'completed': - 'completed' field = true.
            
            // racing excellence is completed when all participants have a place or marked as race_completed = false.
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
