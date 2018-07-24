<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartOrderFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_order_foods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cart_order_id');
            $table->integer('food_id');
            $table->string('custom_name')->nullable();
            $table->integer('food_quantity');
            $table->string('food_option_ids')->nullable();
            $table->string('discount_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_order_foods');
    }
}
