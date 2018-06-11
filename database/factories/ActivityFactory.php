<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Activity::class, function (Faker $faker) {
    return [
    	'activity_type_id' => 1,
        'coach_id' => function() {
			return factory(App\Models\Coach::class)->create()->id;
		},
		// 'activity_type_id'
		'start' => Carbon::parse('2018-11-06 1:00pm'),
		'duration' => 30,
        'end' => Carbon::parse('2018-11-06 1:00pm')->addMinutes(30),
        'location' => 'Cheltenham racecourse'
    ];
});

$factory->state(App\Models\Activity::class, 'upcoming', function($faker) {
	return [
		'start' => Carbon::now()->addMinutes(120),
		'duration' => 30,
        'end' => Carbon::now()->addMinutes(150),
	];
});

$factory->state(App\Models\Activity::class, 'recent', function($faker) {
	return [
		'start' => Carbon::now()->subMinutes(150),
		'duration' => 30,
        'end' => Carbon::now()->subMinutes(120),
	];
});