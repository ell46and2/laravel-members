<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetencyAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competency_assessments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jockey_id')->unsigned()->index();
            $table->integer('coach_id')->unsigned()->index();
            $table->date('start')->index();
            $table->float('riding_rating'); // out of 5 in increments of 0.5
            $table->text('riding_observation')->nullable();
            $table->float('fitness_rating');
            $table->text('fitness_observation')->nullable();
            $table->float('simulator_rating');
            $table->text('simulator_observation')->nullable();
            $table->float('race_riding_skills_rating');
            $table->text('race_riding_skills_observation')->nullable();
            $table->float('weight_rating');
            $table->text('weight_observation')->nullable();
            $table->float('communication_rating');
            $table->text('communication_observation')->nullable();
            $table->float('racing_knowledge_rating');
            $table->text('racing_knowledge_observation')->nullable();
            $table->float('mental_rating');
            $table->text('mental_observation')->nullable();
            $table->text('summary')->nullable();
            $table->timestamps();

            $table->foreign('jockey_id')->references('id')->on('users');
            $table->foreign('coach_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competency_assessments');
    }
}
