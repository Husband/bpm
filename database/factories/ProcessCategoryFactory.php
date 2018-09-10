<?php

use Faker\Generator as Faker;
use ProcessMaker\Models\ProcessCategory;

/**
 * Model factory for a process category.
 */
$factory->define(ProcessCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'status' => $faker->randomElement(
            ['ACTIVE', 'INACTIVE']
        )
    ];
});