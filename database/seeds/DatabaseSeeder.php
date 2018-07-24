<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    protected $toTruncate = ['users','categories','foods','food_options'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->toTruncate as $table)
        {
            DB::table($table)->truncate();
        }

        $this->call(UsersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(FoodsTableSeeder::class);
        $this->call(FoodOptionsTableSeeder::class);
        $this->call(DiscountsTableSeeder::class);
        $this->call(OrderTimesTableSeeder::class);
    }
}
