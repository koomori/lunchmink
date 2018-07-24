<?php

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
use Faker\Provider\en_US\Person;
use Faker\Provider\Lorem;
use Faker\Provider\DateTime;
use Faker\Provider\Base;
use Faker\Provider\en_US\Address;
use Carbon\Carbon;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    $userName = $faker->name;

    return [
        'name' => $userName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'primary_custom_name' => $userName,
        'custom_names' => $userName .','.implode(',', $faker->randomElements($array = array ($faker->name, $faker->name, $faker->name, $faker->name, $faker->name), $count = $faker->numberBetween($min = 0, $max = 5))),
        'delivery_location_street_address'=>$faker->streetAddress,
        'delivery_location_instructions'=>$faker->text,
        'sandwich' => $faker->randomElement($array= array('ham','chicken', 'pork', 'roast beef', 'shrimp','tuna', 'turkey')),
        'soup' => $faker->randomElement($array = array('split pea','pumpkin noodle', 'potato', 'veggie noodle', 'tomato', 'chicken noodle', 'carrot')),
        'beverage' => $faker->randomElement($array= array('sprite 1 liter', 'sprite 2 liter', 'coke 1 liter', 'coke 2 liter', 'pepsi 1 liter', 'pepsi 2 liter', '12 oz coffee', '16 oz coffee')),
        'side'=> $faker->randomElement($array = array ('bread sticks','spinach potato pot stickers','fries', 'vegetable salad')),
        'sweetener' =>$faker->randomElement($array = array ("sweet n'low",'stevia','equal', 'sugar in the raw')),
        'dressing' => $faker->randomElement($array= array('bleu cheese','honey mustard', 'tomato bacon','ranch dressing')),
        'cracker' =>$faker->randomElement($array = array('wheat sesame','wheat','goldfish','saltines')),
        'cheese' => $faker->randomElement($array = array('swiss','provalone','chedder','mozzarella')),
        'spinach' => $faker->numberBetween($min = 0, $max = 1),
        'lettuce' => $faker->numberBetween($min = 0, $max = 1),
        'tomatoes' => $faker->numberBetween($min = 0, $max = 1),
        'green_peppers' => $faker->numberBetween($min = 0, $max = 1),
        'cucumber' => $faker->numberBetween($min = 0, $max = 1),
        'onions' => $faker->numberBetween($min = 0, $max = 1),
        'grated_carrots' => $faker->numberBetween($min = 0, $max = 1),
        'sliced_hard_boiled_egg' => $faker->numberBetween($min = 0, $max = 1),
        'corn_nibblets' => $faker->numberBetween($min = 0, $max = 1),
        'hot_peppers' => $faker->numberBetween($min = 0, $max = 1),
        'pepper' => $faker->numberBetween($min = 0, $max = 1),
        'remember_token' => str_random(10),
        'created_at' => Faker\Provider\DateTime::dateTime($max= 'now'),
        'updated_at' => Faker\Provider\DateTime::dateTime($max= 'now')
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Category::class, function (Faker\Generator $faker) {

          $input = ['soup','sandwich','beverage','cracker','cheese','topping','dressing','sweetner','side'];
          $testValue = array_rand($input, 1);
          $testId = array_search($testValue);
    return [
        'id' => $testId,
        'category_name' => $testValue
    ];
});

$factory->define(App\OrderTime::class, function(Faker\Generator $faker)
{
    $today =  Carbon::now('America/Chicago');
    $year = $today->year;
    $month = $today->month;
    $day = $today->day;

    // set standard start and end times
    $startTime = Carbon::create($year, $month, $day, 9, 0, 0);
    $endTime = Carbon::create(2017, 8, 7, 17, 0, 0);

    // create random times based on start times
    // that don't go over the end time

    $nextTime = $startTime->addHours(rand(0, 7));
    $minutes = $faker->randomElement($array = array (0, 15, 30, 45, 60));
    $nextTime = $nextTime->addMinutes($minutes);

    return [
    'cart_order_id' => $faker->unique()->numberBetween(1, 50),
    'method' => 'pickup',
    'order_time' => $nextTime
    ];

});


