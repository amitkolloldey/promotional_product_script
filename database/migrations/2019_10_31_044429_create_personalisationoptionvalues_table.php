<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalisationoptionvaluesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personalisationoptionvalues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('personalisationoption_id')->index();
            $table->string('value')->nullable();
            $table->foreign('personalisationoption_id')->references('id')->on('personalisation_options')->onDelete('cascade');
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
        Schema::dropIfExists('personalisationoptionvalues');
    }
}
