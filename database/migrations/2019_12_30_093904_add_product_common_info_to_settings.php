<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductCommonInfoToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->longText('delivery_charges')->nullable();
            $table->longText('payment_terms')->nullable();
            $table->longText('return_policy')->nullable();
            $table->longText('disclaimer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('delivery_charges');
            $table->dropColumn('payment_terms');
            $table->dropColumn('return_policy');
            $table->dropColumn('disclaimer');
        });
    }
}
