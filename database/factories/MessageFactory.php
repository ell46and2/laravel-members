<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Message::class, function (Faker $faker) {
    return [
    	'author_id' => function() {
			return factory(App\Models\Coach::class)->create()->id;
		},
		'subject' => 'Message subject',
    	'body' => 'This is the message body',
    ];
});