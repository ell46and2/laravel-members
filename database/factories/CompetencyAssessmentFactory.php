<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\CompetencyAssessment::class, function (Faker $faker) {
    return [
    	'coach_id' => function() {
			return factory(App\Models\Coach::class)->create()->id;
		},
        'jockey_id' => function() {
			return factory(App\Models\Jockey::class)->create()->id;
		},
        'start' => Carbon::now()->addMinutes(120),
        'riding_rating' => 0.5,
        'riding_feedback' => 'riding feedback',
        'fitness_rating' => 1,
        'fitness_feedback' => 'fitness feedback',
        'simulator_rating' => 1.5,
        'simulator_feedback' => 'simulator feedback',
        'race_riding_skills_rating' => 2,
        'race_riding_skills_feedback' => 'race riding skills feedback',
        'weight_rating' => 2.5,
        'weight_feedback' => 'weight feedback',
        'communication_rating' => 3,
        'communication_feedback' => 'communication feedback',
        'racing_knowledge_rating' => 3.5,
        'racing_knowledge_feedback' => 'racing knowledge feedback',
        'mental_rating' => 5,
        'mental_feedback' => 'mental feedback',
        'summary' => 'summary',
    ];
});
