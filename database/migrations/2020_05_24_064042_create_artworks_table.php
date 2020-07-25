<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artworks', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->longText('comment')->nullable();
            $table->text('drive_link')->nullable();
            $table->text('text_to_brand')->nullable();
            $table->string('text_to_brand_font')->nullable();
            $table->unsignedBigInteger('artworkable_id')->index();
            $table->string('artworkable_type');
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
        Schema::dropIfExists('artworks');
    }
}
