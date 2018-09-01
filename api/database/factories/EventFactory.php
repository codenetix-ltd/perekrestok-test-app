<?php

use App\Enums\EventType;
use App\User;
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

$factory->define(App\Event::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'fired_at' => $faker->dateTime,
        'type' => EventType::DEFAULT,
        'is_viewed' => $faker->boolean,
        'is_hidden' => $faker->boolean,
        'message' => $faker->text(100),
        'link' => $faker->url,
    ];
});
