<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalisationtypemarkupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personalisationtypemarkups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('personalisationtype_id')->index();
            $table->unsignedBigInteger('qty_id')->index();
            $table->string('la_price')->nullable();
            $table->string('lb_price')->nullable();
            $table->string('lc_price')->nullable();
            $table->foreign('personalisationtype_id')->references('id')->on('personalisation_types')->onDelete('cascade');
            $table->foreign('qty_id')->references('id')->on('quantity')->onDelete('cascade');
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
        Schema::dropIfExists('personalisationtypemarkups');
    }
}
