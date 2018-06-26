<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Attachment::class, function (Faker $faker) {
    return [
    	'uid' => $uid = uniqid(true),
        'attachable_id' => 1,
        'attachable_type' => 'activity',
        'processed' => 1,
        'filename' => $uid . '.mp4',
        'filetype' => 'video',
    ];
});