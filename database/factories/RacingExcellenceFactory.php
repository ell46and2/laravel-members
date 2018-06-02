<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\RacingExcellence::class, function (Faker $faker) {
    return [
        'coach_id' => function() {
			return factory(App\Models\Coach::class)->create()->id;
		},
        'location' => 'Cheltenham racecourse',
        'start' => Carbon::parse('2018-11-06 1:00pm'),
    ];
});

$factory->define(App\Models\RacingExcellenceDivision::class, function (Faker $faker) {
    return [
        'racing_excellence_id' => function() {
			return factory(App\Models\RacingExcellence::class)->create()->id;
		},
    ];
});
