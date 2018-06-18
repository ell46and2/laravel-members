<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Comment::class, function (Faker $faker) {
    return [
		'commentable_id' => function() {
			return factory(App\Models\Activity::class)->create()->id;
		},
        'commentable_type' => 'activity',
		'author_id' => function() {
			return factory(App\Models\Coach::class)->create()->id;
		},
		'recipient_id' => function() {
			return factory(App\Models\Jockey::class)->create()->id;
		},
		'body' => $faker->text($maxNbChars = 200),
		'read' => false,
		'private' => false,
    ];
});
