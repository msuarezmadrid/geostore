<?php

use App\User;
use App\Item;
use App\Adjustment;
use App\AdjustmentItem;
use App\Enterprise;
use App\ItemPrice;
use App\Location;
use App\LocationItem;
use App\PurchaseOrder;
use App\PurchaseOrderItem;
use App\SaleOrder;
use App\SaleOrderItem;
use App\Supplier;
use App\Transfer;
use App\TransferItem;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'verified' => $verified = $faker->randomElement([true, false]),
        'verification_token' => $verified == true ? null: User::generateVerificationToken(),
        'admin' => 0,
        'remember_token' => str_random(10),
    ];
});

