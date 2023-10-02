<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fav_collection_fav_post', function (Blueprint $table) {
            $table->unsignedBigInteger('favourite_post_id');
            $table->foreign('favourite_post_id')->references('favourite_post_id')->on('favourite_post');
            $table->unsignedBigInteger('favourite_collection_id');
            $table->foreign('favourite_collection_id')->references('favourite_collection_id')->on('favourite_collection');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fav_collection_fav_post');
    }
};
