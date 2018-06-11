<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Document::class, function (Faker $faker) {
    return [
        'title' => 'Document title',
        'document_filename' => 'document.pdf'
    ];
});
