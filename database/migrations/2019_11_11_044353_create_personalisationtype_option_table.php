<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalisationtypeOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personalisationtype_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('personalisationtype_id')->index();
            $table->unsignedBigInteger('personalisationoption_id')->nullable()->index();
            $table->unsignedBigInteger('personalisationoptionvalue_id')->nullable()->index();
            $table->foreign('personalisationtype_id')->references('id')->on('personalisation_types')->onDelete('cascade');
            $table->foreign('personalisationoption_id')->references('id')->on('personalisation_options')->onDelete('cascade');
            $table->foreign('personalisationoptionvalue_id', 'pov_id_foreign')->references('id')->on('personalisationoptionvalues')->onDelete('cascade');
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
        Schema::dropIfExists('personalisationtype_options');
    }
}
