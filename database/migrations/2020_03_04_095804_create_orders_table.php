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
            $table->bigIncrements('id');
            $table->string('order_no');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('company');
            $table->string('address');
            $table->string('suburb');
            $table->string('state');
            $table->string('postcode');
            $table->string('shipping_company')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_suburb')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_postcode')->nullable();
            $table->string('shipping_same_as_billing')->nullable();
            $table->string('how_you_hear');
            $table->string('quantity');
            $table->string('color')->nullable();
            $table->string('personalisation_options');
            $table->string('personalisation_color');
            $table->string('total_price')->nullable();
            $table->string('unit_price')->nullable();
            $table->string('status')->default('pending');
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
