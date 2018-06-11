<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Message::class, function (Faker $faker) {
    return [
    	'author_id' => function() {
			return factory(App\Models\Coach::class)->create()->id;
		},
        'recipient_id' => function() {
			return factory(App\Models\Jockey::class)->create()->id;
		},
		'subject' => 'Message subject',
    	'body' => 'This is the message body',
    ];
});

$factory->state(App\Models\Message::class, 'read', function($faker) {
	return [
		'read' => true
	];
});