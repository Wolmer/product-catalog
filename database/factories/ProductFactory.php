<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'category_id' => App\Models\Category::all()->random()->id,
        'name'        => $faker->name(),
        'description' => $faker->sentence(),
        'price'       => $faker->randomDigit(2, 0, 9999),
        'image'       => $faker->image(),
    ];
});
