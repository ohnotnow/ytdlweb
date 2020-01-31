<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DownloadedFile;
use Faker\Generator as Faker;

$factory->define(DownloadedFile::class, function (Faker $faker) {
    return [
        'title' => $faker->text(40),
        'url' => $faker->url,
    ];
});
