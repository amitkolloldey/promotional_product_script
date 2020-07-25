<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorPriceIncludedToPersonalisationType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personalisation_types', function (Blueprint $table) {
            $table->string('is_color_price_included')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personalisation_types', function (Blueprint $table) {
            $table->dropColumn('is_color_price_included');
        });
    }
}
