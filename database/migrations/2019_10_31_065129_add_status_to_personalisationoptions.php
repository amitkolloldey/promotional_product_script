<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToPersonalisationoptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personalisation_options', function (Blueprint $table) {
            $table->string('status')->default('1')->nullable();
            $table->string('printing')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personalisation_options', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('printing');
        });
    }
}
