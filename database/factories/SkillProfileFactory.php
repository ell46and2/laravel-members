<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\SkillProfile::class, function (Faker $faker) {
    return [
    	'coach_id' => function() {
			return factory(App\Models\Coach::class)->create()->id;
		},
        'jockey_id' => function() {
			return factory(App\Models\Jockey::class)->create()->id;
		},
        'start' => Carbon::now()->addMinutes(120),
        'riding_rating' => 0.5,
        'riding_observation' => 'riding observation',
        'fitness_rating' => 1,
        'fitness_observation' => 'fitness observation',
        'simulator_rating' => 1.5,
        'simulator_observation' => 'simulator observation',
        'race_riding_skills_rating' => 2,
        'race_riding_skills_observation' => 'race riding skills observation',
        'weight_rating' => 2.5,
        'weight_observation' => 'weight observation',
        'communication_rating' => 3,
        'communication_observation' => 'communication observation',
        'whip_rating' => 3.5,
        'whip_observation' => 'racing knowledge observation',
        'professionalism_rating' => 5,
        'professionalism_observation' => 'mental observation',
        'summary' => 'summary',
    ];
});
