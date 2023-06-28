<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Employee;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Employee::class, function (Faker $faker) {

    return [
        //
        'emp_id' => $faker->numberBetween(10000, 99999),
        'emp_name' => $faker->name,
        'password' => Hash::make('password'),
    ];
});
