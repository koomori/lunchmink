<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cart_order_id')->nullable();
            $table->integer('repeated_order_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('order_name')->nullable();
            $table->string('order_time_held_until')->nullable();
            $table->dateTime('pickup_time')->nullable();
            $table->dateTime('delivery_time')->nullable();
            $table->string('delivery_location_street_address')->nullable();
            $table->string('delivery_location_instructions')->nullable();
            $table->float('sales_tax_total')->nullable();
            $table->float('local_tax_total')->nullable();
            $table->string('order_total')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
