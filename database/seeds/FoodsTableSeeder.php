<?php

use Illuminate\Database\Seeder;

class FoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
      $foods = array (
        array(
        'category_id' => 2, 'sub_category_name' => 'none', 'food_name'=> 'ham', 'food_description'=> 'two thick slices of freshly roasted ham', 'price'=> 7.89
        ),
        array(
        'category_id' => 2, 'sub_category_name' => 'none', 'food_name'=> 'chicken', 'food_description'=> 'two sliced chicken breast fillets roasted daily', 'price'=> 7.89
        ),
        array(
        'category_id' => 2 , 'sub_category_name' => 'none', 'food_name'=> 'pork', 'food_description'=> 'thick pork slices roasted daily', 'price'=> 7.89
        ),
        array(
        'category_id' => 2, 'sub_category_name' => 'none', 'food_name'=> 'roast beef', 'food_description'=> 'hearty slices of roast beef, tender and tasy', 'price'=> 7.89
        ),
        array(
        'category_id' => 2, 'sub_category_name' => 'none', 'food_name'=> 'shrimp', 'food_description'=>'eight bite-sized shrimp marinated in butter','price'=> 7.89
        ),
        array(
        'category_id' => 2, 'sub_category_name' => 'none', 'food_name'=> 'tuna', 'food_description'=> 'Tuna mixed with mayonnaise of our own making', 'price'=> 7.89
        ),
        array(
        'category_id' => 2, 'sub_category_name' => 'none', 'food_name'=> 'turkey', 'food_description'=> 'roasted turkey slices prepared daily', 'price'=> 7.89
        ),
        array(
        'category_id' => 1, 'sub_category_name' => 'none', 'food_name'=> 'split pea', 'food_description'=> 'Thick soup nice and green', 'price'=> 5.40
        ),
        array(
        'category_id' => 1, 'sub_category_name' => 'none', 'food_name'=> 'pumpkin noodle', 'food_description'=>  'pumpkin is year round as far as we are concerned with a hearty flavor, with butter and chives', 'price'=> 5.45
        ),
        array(
        'category_id' => 1, 'sub_category_name' => 'none', 'food_name'=> 'potato', 'food_description'=> 'Thick delicious potato soup including cubed chunks of russet potatoes', 'price'=> 5.40
        ),
        array(
        'category_id' => 1, 'sub_category_name' => 'none', 'food_name'=> 'veggie noodle', 'food_description'=> 'vegetable soup with spectacular spiral noodles', 'price'=> 5.40
        ),
        array(
        'category_id' => 1, 'sub_category_name' => 'none', 'food_name'=> 'tomato', 'food_description'=> 'tomato soup with tomato chunks', 'price'=> 5.45
        ),
        array(
        'category_id' => 1, 'sub_category_name' => 'none', 'food_name'=> 'chicken noodle', 'food_description'=>'chicken noodle soup with egg noodles great for all your chicken noodle needs', 'price'=> 5.81
        ),
        array(
        'category_id' => 1, 'sub_category_name' => 'none', 'food_name'=> 'carrot', 'food_description'=> 'carrot soup with chunks of cubed carrots', 'price'=> 5.72
        ),
        array(
        'category_id' => 3, 'sub_category_name' => 'none', 'food_name'=>'sprite 1 liter', 'food_description'=> '1 liter of thirst fulfulling sprite', 'price'=> 1.99
        ),
        array(
        'category_id' => 3, 'sub_category_name' => 'none', 'food_name'=> 'sprite 2 liter', 'food_description'=> 'A great amount of sprite for you!', 'price'=> 2.99
        ),
         array(
        'category_id' => 3, 'sub_category_name' => 'none', 'food_name'=> '1 liter coke', 'food_description'=> "the fluid you've come to know and love", 'price'=> 1.99
        ),
        array(
        'category_id' => 3, 'sub_category_name' => 'none', 'food_name'=> '2 liters of coke', 'food_description' => 'More coke for your entire day, unless you really swig it. Then you can get more.' , 'price' => 2.99
        ),
         array(
        'category_id' => 3, 'sub_category_name' => 'none', 'food_name'=> '1 liter of pepsi', 'food_description'=> 'Pepsi as it should be', 'price'=> 1.99
        ),
          array(
        'category_id' => 3, 'sub_category_name' => 'none', 'food_name'=> '2 liters of pepsi', 'food_description'=> 'A big plastic bottle containing pepsi.', 'price'=> 2.99
        ),
           array(
        'category_id' => 3, 'sub_category_name' => 'coffee', 'food_name'=> '12 oz of Coffee ', 'food_description'=> 'A small dose of caffeine for your day', 'price'=> 1.12
        ),
          array(
         'category_id' => 3, 'sub_category_name' => 'coffee', 'food_name'=> '16 oz of Coffee', 'food_description'=> 'A larger dose of caffeine for your day', 'price'=> 1.45
        )
       );
        DB::table('foods')->insert($foods);
    }
}
