<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalisationTypePrintingAgencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_t_pr_a', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('personalisationtype_id')->index();
            $table->unsignedBigInteger('printingagency_id')->nullable()->index();
            $table->foreign('personalisationtype_id','pot_id_foreign')->references('id')->on('personalisation_types')->onDelete('cascade');
            $table->foreign('printingagency_id','pta_id_foreign')->references('id')->on('printing_agencies')->onDelete('cascade');
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
        Schema::dropIfExists('p_t_pr_a');
    }
}
