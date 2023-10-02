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
        Schema::create('favourite_post', function (Blueprint $table) {
            $table->bigIncrements('favourite_post_id');
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('client_id')->on('client');
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('post_id')->on('post');
            $table->unsignedInteger('collection_count')->default(0);
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
        Schema::dropIfExists('favourite_post');
    }
};
