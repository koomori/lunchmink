<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

      $data = array(
        array('category_name' => 'soup'),
        array('category_name' => 'sandwich'),
        array('category_name' => 'beverage'),
        array('category_name' => 'salad dressing')
      );

      DB::table('categories')->insert($data);


    }
}
