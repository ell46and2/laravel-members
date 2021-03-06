<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Notification::class, function (Faker $faker) {
    return [
        'user_id' => function() {
			return factory(App\Models\Jockey::class)->create()->id;
		},
		'body' => $faker->text($maxNbChars = 200),
		'read' => false,
		'notifiable_id' => null,
		'notifiable_type' => null,
		'created_at' => Carbon::now(),
    ];
});
