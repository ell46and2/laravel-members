<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Invoice::class, function (Faker $faker) {
    return [
        'coach_id' => function() {
			return factory(App\Models\Coach::class)->create()->id;
		},
    ];
});