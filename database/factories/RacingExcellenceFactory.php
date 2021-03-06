<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\RacingExcellence::class, function (Faker $faker) {
    return [
        'coach_id' => function() {
			return factory(App\Models\Coach::class)->create()->id;
		},
        'raceId' => $faker->numberBetween($min = 1000, $max = 9000),
        'yearOfRace' => now()->year,
        // 'location_id' => 1,
        'location' => 'Wolverhampton',
        'series_name' => 'Hands and Heels All Weather Series',
        // 'series_id' => 1,
        'start' => Carbon::parse('2018-11-06 1:00pm'),
        'completed' => false,
    ];
});

$factory->define(App\Models\RacingExcellenceDivision::class, function (Faker $faker) {
    return [
        'racing_excellence_id' => function() {
			return factory(App\Models\RacingExcellence::class)->create()->id;
		},
        'division_number' => 1
    ];
});

$factory->state(App\Models\RacingExcellence::class, 'upcoming', function($faker) {
	return [
		'start' => Carbon::now()->addMinutes(120),
	];
});

$factory->state(App\Models\RacingExcellence::class, 'recent', function($faker) {
	return [
		'start' => Carbon::now()->subMinutes(120),
	];
});

$factory->define(App\Models\RacingLocation::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});

$factory->define(App\Models\RacingExcellenceParticipant::class, function (Faker $faker) {
    return [
        'racing_excellence_id' => function() {
            return factory(App\Models\RacingExcellence::class)->create()->id;
        },
        'division_id' => function() {
            return factory(App\Models\RacingDivision::class)->create([
                'racing_excellence_id' => 1
            ])->id;
        },
        'jockey_id' => function() {
            return factory(App\Models\Jockey::class)->create()->id;
        },
        'api_id' => $faker->numberBetween($min = 1000, $max = 90000),
        'name' => null,
        'place' => null,
        'completed_race' => true,
        'presentation_points' => null,
        'professionalism_points' => null,
        'coursewalk_points' => null,
        'riding_points' => null,
        'total_points' => null, 
    ];
});


