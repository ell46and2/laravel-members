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

$factory->define(App\Models\InvoiceLine::class, function (Faker $faker) {
    return [
        'invoice_id' => function() {
			return factory(App\Models\Invoice::class)->create()->id;
		},
		'invoiceable_id' => null,
		'invoiceable_type' => null,
		'name' => null,
		'value' => null
    ];
});