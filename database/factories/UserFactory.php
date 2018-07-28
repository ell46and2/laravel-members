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

$factory->define(App\Models\User::class, function (Faker $faker) {
	static $password;

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'gender' => 'male',
        'address_1' => $faker->secondaryAddress,
        'address_2' => $faker->streetAddress,
        'county_id' => 26,
        'country_id' => 1,
        'nationality_id' => 1,
        'postcode' => $faker->postcode,
        'telephone' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'county_id' => 1,
        'country_id' => 1,
    ];
});

// Jockey user
$factory->define(App\Models\Jockey::class, function (Faker $faker) {
	static $password;

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'gender' => 'male',
        'address_1' => $faker->secondaryAddress,
        'address_2' => $faker->streetAddress,
        'county_id' => 26,
        'country_id' => 1,
        'nationality_id' => 1,
        'postcode' => $faker->postcode,
        'telephone' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'role_id' => 1, // jockey
        'licence_date' => null
    ];
});

$factory->define(App\Models\Coach::class, function (Faker $faker) {
	static $password;

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'gender' => 'male',
        'address_1' => $faker->secondaryAddress,
        'address_2' => $faker->streetAddress,
        'county_id' => 26,
        'country_id' => 1,
        'nationality_id' => 1,
        'postcode' => $faker->postcode,
        'telephone' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'role_id' => 2, // coach
		'approved' => true
    ];
});

$factory->define(App\Models\Admin::class, function (Faker $faker) {
    static $password;

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'gender' => 'male',
        'address_1' => $faker->secondaryAddress,
        'address_2' => $faker->streetAddress,
        'county_id' => 26,
        'country_id' => 1,
        'nationality_id' => 1,
        'postcode' => $faker->postcode,
        'telephone' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'role_id' => 3, // coach
        'approved' => true
    ];
});

$factory->define(App\Models\Jet::class, function (Faker $faker) {
    static $password;

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'gender' => 'male',
        'address_1' => $faker->secondaryAddress,
        'address_2' => $faker->streetAddress,
        'county_id' => 26,
        'country_id' => 1,
        'nationality_id' => 1,
        'postcode' => $faker->postcode,
        'telephone' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'role_id' => 4, // jets
        'approved' => true
    ];
});

$factory->state(App\Models\Jockey::class, 'approved', function($faker) {
	return [
		'approved' => true
	];
});

$factory->state(App\Models\Coach::class, 'with_token', function($faker) {
    return [
        'access_token' => str_random(100)
    ];
});