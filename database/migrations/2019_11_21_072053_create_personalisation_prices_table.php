<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalisationPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personalisation_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('personalisationtype_id')->nullable()->index();
            $table->unsignedBigInteger('printingagency_id')->nullable()->index();
            $table->unsignedBigInteger('size_id')->nullable()->index();
            $table->string('color_position_id')->nullable()->comment('Comma Separated Color ID and Price ID')->index();
            $table->unsignedBigInteger('quantity_id')->nullable()->index();
            $table->double('price')->nullable();
            $table->foreign('personalisationtype_id','pt_id_foreign')->references('id')->on('personalisation_types')->onDelete('cascade');
            $table->foreign('printingagency_id','pa_id_foreign')->references('id')->on('printing_agencies')->onDelete('cascade');
            $table->foreign('quantity_id')->references('id')->on('quantity')->onDelete('cascade');
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
        Schema::dropIfExists('personalisation_prices');
    }
}
