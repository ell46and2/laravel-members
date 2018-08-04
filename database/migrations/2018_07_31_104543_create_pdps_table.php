<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePdpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jockey_id')->unsigned()->index();
            $table->dateTime('submitted')->nullable();
            $table->enum('status', [
                "In Progress",
                "Awaiting Review",
                "Completed"
            ])->default("In Progress");

            //Personl Details
            $table->text('currently_studying')->nullable();
            $table->text('education')->nullable();
            $table->string('path_into_racing')->nullable();
            $table->string('time_in_racing')->nullable();
            $table->boolean('personal_details_page_complete')->default(false);

            //Career
            $table->boolean('weight')->default(false);
            $table->boolean('confidence')->default(false);
            $table->boolean('strength_fitness')->default(false);
            $table->boolean('racing_preparation')->default(false);
            $table->boolean('diet')->default(false);
            $table->text('career_other')->nullable();
            $table->text('long_term_goal')->nullable();
            $table->boolean('career_page_complete')->default(false);

            //Nutrition
            $table->integer('balanced_diet')->nullable();
            $table->text('balanced_diet_comment')->nullable();
            $table->integer('diet_plan')->nullable();
            $table->text('diet_plan_comment')->nullable();
            $table->integer('making_weight')->nullable();
            $table->text('making_weight_comment')->nullable();
            $table->integer('food_label')->nullable();
            $table->text('food_label_comment')->nullable();
            $table->boolean('food_diary')->default(false);
            $table->text('food_diary_comment')->nullable();
            $table->boolean('cooking_classes')->default(false);
            $table->text('cooking_classes_comment')->nullable();
            $table->boolean('one_to_one')->default(false);
            $table->text('one_to_one_comment')->nullable();
            $table->boolean('alcohol_education')->default(false);
            $table->text('alcohol_education_comment')->nullable();
            $table->text('nutrition_notes')->nullable();
            $table->boolean('nutrition_page_complete')->default(false);

            //Physical – Strength and Conditioning
            $table->boolean('physical')->default(false);
            $table->text('physical_comment')->nullable();
            $table->text('physical_notes')->nullable();
            $table->boolean('physical_page_complete')->default(false);

            //Communication and Media
            $table->integer('interview_skills')->nullable();
            $table->text('interview_skills_comment')->nullable();
            $table->integer('communication_skills')->nullable();
            $table->text('communication_skills_comment')->nullable();
            $table->integer('social_media')->nullable();
            $table->text('social_media_comment')->nullable();
            $table->boolean('media_training')->default(false);
            $table->text('media_training_comment')->nullable();
            $table->boolean('social_media_training')->default(false);
            $table->text('social_media_training_comment')->nullable();
            $table->text('communication_notes')->nullable();
            $table->boolean('communication_media_page_complete')->default(false);

            //Personal
            $table->text('longterm_goals')->nullable();
            $table->boolean('personal_well_being_page_complete')->default(false);

            //Managing Finances
            $table->integer('financial')->nullable();
            $table->text('financial_comment')->nullable();
            $table->integer('budgeting')->nullable();
            $table->text('budgeting_comment')->nullable();
            $table->integer('sponsorship')->nullable();
            $table->text('sponsorship_comment')->nullable();
            $table->integer('accountant_tax')->nullable();
            $table->text('accountant_tax_comment')->nullable();
            $table->integer('savings')->nullable();
            $table->text('savings_comment')->nullable();
            $table->enum('pja_saving_plan', [
                "I have one",
                "I’m in the process of organising one",
                "I don’t have one"
            ])->nullable();
            $table->enum('pja_pension', [
                "I have one",
                "I’m in the process of organising one",
                "I don’t have one"
            ])->nullable();
            $table->text('finances_notes')->nullable();
            $table->boolean('managing_finance_page_complete')->default(false);

            //Sports Psychology
            $table->integer('psychology_confidence')->nullable();
            $table->text('psychology_confidence_comment')->nullable();
            $table->integer('positive_attitude')->nullable();
            $table->text('positive_attitude_comment')->nullable();
            $table->integer('goal_setting')->nullable();
            $table->text('goal_setting_comment')->nullable();
            $table->integer('resilience')->nullable();
            $table->text('resilience_comment')->nullable();
            $table->text('sports_psychology_notes')->nullable();
            $table->boolean('sports_psychology_page_complete')->default(false);

            //Mental Well-being
            $table->integer('concentration')->nullable();
            $table->text('concentration_comment')->nullable();
            $table->integer('relaxation')->nullable();
            $table->text('relaxation_comment')->nullable();
            $table->integer('social')->nullable();
            $table->text('social_comment')->nullable();
            $table->integer('community')->nullable();
            $table->text('community_comment')->nullable();
            $table->text('mental_notes')->nullable();
            $table->boolean('mental_well_being_page_complete')->default(false);

            //Interests and Hobbies
            $table->text('interests_hobbies')->nullable();
            $table->boolean('interests_hobbies_page_complete')->default(false);

            //Performance Goals
            $table->string('three_months_1')->nullable();
            $table->boolean('three_months_1_achieved')->default(false);
            $table->string('three_months_2')->nullable();
            $table->boolean('three_months_2_achieved')->default(false);
            $table->string('three_months_3')->nullable();
            $table->boolean('three_months_3_achieved')->default(false);
            $table->string('six_months_1')->nullable();
            $table->boolean('six_months_1_achieved')->default(false);
            $table->string('six_months_2')->nullable();
            $table->boolean('six_months_2_achieved')->default(false);
            $table->string('six_months_3')->nullable();
            $table->boolean('six_months_3_achieved')->default(false);
            $table->string('twelve_months_1')->nullable();
            $table->boolean('twelve_months_1_achieved')->default(false);
            $table->string('twelve_months_2')->nullable();
            $table->boolean('twelve_months_2_achieved')->default(false);
            $table->string('twelve_months_3')->nullable();
            $table->boolean('twelve_months_3_achieved')->default(false);
            $table->string('two_years_1')->nullable();
            $table->boolean('two_years_1_achieved')->default(false);
            $table->string('two_years_2')->nullable();
            $table->boolean('two_years_2_achieved')->default(false);
            $table->string('two_years_3')->nullable();
            $table->boolean('two_years_3_achieved')->default(false);
            $table->boolean('performance_goals_page_complete')->default(false);
            
            //Actions
            $table->text('jets_actions')->nullable();
            $table->boolean('jets_actions_completed')->default(false);
            $table->text('support_team_actions')->nullable();
            $table->boolean('support_team_actions_completed')->default(false);
            $table->boolean('actions_page_complete')->default(false);

            //Present Support Team
            $table->string('family')->nullable();
            $table->string('friends')->nullable();
            $table->string('partner')->nullable();
            $table->string('employer')->nullable();
            $table->string('jockey_coach')->nullable();
            $table->string('agent')->nullable();
            $table->string('physio')->nullable();
            $table->string('coach_fitness_trainer')->nullable();
            $table->boolean('support_team_page_complete')->default(false);

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
        Schema::dropIfExists('pdps');
    }
}
