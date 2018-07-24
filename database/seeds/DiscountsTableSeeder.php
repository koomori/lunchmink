<?php

use Illuminate\Database\Seeder;

class DiscountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
      $discounts = array(
        array('discount_description' => '8th coffee is free'),
        array('discount_description' => '8th sandwich is free')
        );

      DB::table('discounts')->insert($discounts);
    }
}
