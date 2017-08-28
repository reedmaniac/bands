<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(App\Models\Band::class, function (Faker\Generator $faker) {
    return [
        'name' => ucwords($faker->unique()->words(rand(1,4), true)),
        'start_date' => $faker->date,
        'website' => $faker->url,
        'still_active' => (rand(1, 10) == 1) ? false : true,
    ];
});

$factory->define(App\Models\Album::class, function (Faker\Generator $faker) {
    return [
        'name' => ucwords($faker->catchPhrase),
        'recorded_date' => $faker->date,
        'release_date' => $faker->date,
        'number_of_tracks' => rand(1,5),
        'producer' => $faker->name,
        'genre' => $faker->randomElement(App\Libraries\Albums::$genres)
    ];
});
