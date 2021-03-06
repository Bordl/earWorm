<?php

use Faker\Generator as Faker;


$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'user_id'           =>  function () { return factory('App\User')->create()->id; },
        'post_id'           =>  function () { return factory('App\User')->create()->id; },
        'body'              => $faker->paragraph,
        'link'              => $faker->url,
    ];
});
