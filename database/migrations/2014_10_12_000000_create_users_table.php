<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('primary_custom_name')->nullable();
            $table->text('custom_names')->nullable();
            $table->string('delivery_location_street_address')->nullable();
            $table->text('delivery_location_instructions')->nullable();
            $table->string('sandwich')->nullable();
            $table->string('soup')->nullable();
            $table->string('beverage')->nullable();
            $table->string('side')->nullable();
            $table->string('sweetener')->nullable();
            $table->string('dressing')->nullable();
            $table->string('cracker')->nullable();
            $table->string('cheese')->nullable();
            $table->tinyInteger('spinach')->nullable();
            $table->tinyInteger('lettuce')->nullable();
            $table->tinyInteger('tomatoes')->nullable();
            $table->tinyInteger('green_peppers')->nullable();
            $table->tinyInteger('cucumber')->nullable();
            $table->tinyInteger('onions')->nullable();
            $table->tinyInteger('grated_carrots')->nullable();
            $table->tinyInteger('sliced_hard_boiled_egg')->nullable();
            $table->tinyInteger('corn_nibblets')->nullable();
            $table->tinyInteger('hot_peppers')->nullable();
            $table->tinyInteger('pepper')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
