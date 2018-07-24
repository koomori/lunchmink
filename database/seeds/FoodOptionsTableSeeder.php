<?php

use Illuminate\Database\Seeder;

class FoodOptionsTableSeeder extends Seeder
{
    public function run()
    {
      //create array of food options data
      $foodOptions = array(
        array('type'=> 'topping', 'category_id'=> 2, 'option_name' => 'spinach', 'option_description' => 'fresh baby spinach'),
        array('type'=> 'topping', 'category_id'=> 2, 'option_name' => 'lettuce', 'option_description' => 'freshly chopped iceberg lettuce'),
        array('type'=> 'topping', 'category_id'=> 2, 'option_name' => 'tomatoes', 'option_description' => 'organic tomatoes'),
        array('type'=> 'topping', 'category_id'=> 2, 'option_name' => 'green peppers', 'option_description' => 'fresh and sliced long green peppers'),
        array('type'=> 'topping', 'category_id'=>2, 'option_name' => 'cucumber', 'option_description' => 'cucumbers sliced round and as fresh as we can get them'),
        array('type'=> 'topping', 'category_id'=> 2, 'option_name' => 'onions' , 'option_description' => 'sliced thin and spread evenly' ),
        array('type'=> 'topping', 'category_id'=> 2, 'option_name' => 'grated carrots', 'option_description' => 'a layer of expertly peeled and delightfully grated carrots'),
        array('type'=> 'topping', 'category_id'=> 2, 'option_name' => 'sliced hard boiled egg', 'option_description' => "half a sliced boiled egg sliced into 'wagon wheels' and distributed across your sandwich"),
        array('type'=> 'topping', 'category_id'=> 2, 'option_name' => 'corn nibblets', 'option_description' => 'yellow corn nibblets brightening your sandwich'),
        array('type'=> 'topping', 'category_id'=> 2, 'option_name' => 'hot peppers', 'option_description' => 'red hot peppers of medium strength'),
        array('type'=> 'topping', 'category_id'=> 2, 'option_name' => 'pepper', 'option_description' => 'ground black pepper in a packet'),
        array('type' => 'sweetener', 'category_id'=>3, 'option_name' => "sweet n'low", 'option_description' => 'in the pink packet'),
        array('type' => 'sweetener', 'category_id'=> 3, 'option_name' => 'stevia', 'option_description' => 'natural non gmo sweetener'),
        array('type' => 'sweetener', 'category_id'=> 3, 'option_name' => 'equal' , 'option_description' => 'contains aspartame'),
        array('type' => 'sweetener', 'category_id'=> 3, 'option_name' => 'sugar in the raw' , 'option_description' => 'natural cane sugar'),
        array('type' => 'dressing', 'category_id'=> 4, 'option_name' => 'bleu cheese', 'option_description' => 'great bleu cheese dressing' ),
        array('type' => 'dressing', 'category_id'=> 4, 'option_name' => 'honey mustard', 'option_description' => 'made with honey from local hives'),
        array('type' => 'dressing', 'category_id'=> 4, 'option_name' => 'tomato bacon', 'option_description' => 'bacon bits are prepared daily'),
        array('type' => 'dressing', 'category_id'=> 4, 'option_name' => 'ranch dressing', 'option_description' => 'sour cream, chives and onions'),
        array('type' => 'cracker', 'category_id'=> 1, 'option_name' => 'wheat sesame', 'option_description' => 'whole wheat crackers dusted with sesame seeds'),
        array('type' => 'cracker', 'category_id'=> 1, 'option_name' => 'goldfish', 'option_description' => 'a panoply of goldfish crackers'),
        array('type' => 'cracker', 'category_id'=> 1, 'option_name' => 'saltines', 'option_description' => 'great with everything'),
        array('type' => 'cracker', 'category_id'=> 1,'option_name' => 'wheat', 'option_description' => 'whole wheat crackers, naked'),
         array('type' => 'cheese', 'category_id'=> 2, 'option_name' => 'swiss', 'option_description' => 'they contain holes, as you would expect'),
        array('type' => 'cheese', 'category_id'=> 2, 'option_name' => 'provalone', 'option_description' => 'has a bit of tang and a bit of smooth'),
        array('type' => 'cheese', 'category_id'=> 2, 'option_name' => 'chedder', 'option_description' => 'high quality cheddar cheese squares, neither dry nor made of plastic'),
        array('type' => 'cheese', 'category_id'=> 2, 'option_name' => 'mozzarella', 'option_description' => 'oval slices of high quality mozzarella'),
        array('type' => 'side', 'category_id'=>2, 'option_name' => 'bread sticks', 'option_description' => 'buttered garlic bread sticks prepared fresh daily'),
        array('type' => 'side', 'category_id'=>2, 'option_name' => 'spinach potato pot stickers', 'option_description' => 'our most popular side prepared with organic spinach and potatoes'),
        array('type' => 'side', 'category_id'=>2, 'option_name' => 'fries', 'option_description' => 'thick cut fries from Idaho potatoes, no trans fats'),
        array('type' => 'side', 'category_id'=>2, 'option_name' => 'vegetable salad', 'option_description' => 'contains lots of good stuff as you can see in the picture')
      );

      DB::table('food_options')->insert($foodOptions);
    }
}
