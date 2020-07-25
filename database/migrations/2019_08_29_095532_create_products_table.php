<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->string('product_type');
            $table->string('product_code');
            $table->string('dimensions')->nullable();
            $table->string('video_link')->nullable();
            $table->string('print_area')->nullable();
            $table->string('main_image')->nullable();
            $table->longText('short_desc')->nullable();
            $table->longText('long_desc')->nullable();
            $table->longText('product_features')->nullable();
            $table->longText('decoration_areas')->nullable();
            $table->longText('delivery_charges')->nullable();
            $table->longText('payment_terms')->nullable();
            $table->longText('return_policy')->nullable();
            $table->longText('disclaimer')->nullable();
            $table->double('min_price')->nullable();
            $table->double('max_price')->nullable();
            $table->integer('status')->default('1');
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
        Schema::dropIfExists('products');
    }
}
