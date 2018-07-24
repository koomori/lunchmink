<?php

use Illuminate\Database\Seeder;

class OrderTimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
      $orderTimes = factory(App\OrderTime::class, 50)->create();
    }
}
