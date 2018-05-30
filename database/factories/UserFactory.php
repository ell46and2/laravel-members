<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

// Jockey user
$factory->define(App\Models\User::class, function (Faker $faker) {
	static $password;

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'telephone' => $faker->phoneNumber,
    	'street_address' => $faker->secondaryAddress,
    	'city' => $faker->city,
    	'postcode' => $faker->postcode,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->state(App\Models\User::class, 'jockey', function($faker) {
	return [
		'role_id' => 1, // jockey
	];
});

$factory->state(App\Models\User::class, 'coach', function($faker) {
	return [
		'role_id' => 2, // coach
		'approved' => true
	];
});

$factory->state(App\Models\User::class, 'admin', function($faker) {
	return [
		'role_id' => 3, // admin
		'approved' => true
	];
});

$factory->state(App\Models\User::class, 'approved', function($faker) {
	return [
		'approved' => true
	];
});
