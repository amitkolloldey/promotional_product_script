<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsbTypePurchasePrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usb_purchase_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('usb_type_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->unsignedBigInteger('quantity_id')->nullable()->index();
            $table->double('price')->nullable();
            $table->foreign('usb_type_id')->references('id')->on('usb_types')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('usb_purchase_prices');
    }
}
