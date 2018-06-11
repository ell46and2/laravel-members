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
            $table->integer('coach_id');
            $table->date('start')->index();
            //$table->string('location')->nullable(); // Possibly not needed or maybe a dropdown list
            $table->float('riding_rating'); // out of 5 in increments of 0.5
            $table->text('riding_feedback');
            $table->float('fitness_rating');
            $table->text('fitness_feedback');
            $table->float('simulator_rating');
            $table->text('simulator_feedback');
            $table->float('race_riding_skills_rating');
            $table->text('race_riding_skills_feedback');
            $table->float('weight_rating');
            $table->text('weight_feedback');
            $table->float('communication_rating');
            $table->text('communication_feedback');
            $table->float('racing_knowledge_rating');
            $table->text('racing_knowledge_feedback');
            $table->float('mental_rating');
            $table->text('mental_feedback');
            $table->text('summary');
            $table->timestamps();
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
