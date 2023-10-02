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
        Schema::create('favourite_collection', function (Blueprint $table) {
            $table->bigIncrements('favourite_collection_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('user');
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
        Schema::dropIfExists('favourite_collection');
    }
};
