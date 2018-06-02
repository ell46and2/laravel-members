<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Activity::class, function (Faker $faker) {
    return [
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
