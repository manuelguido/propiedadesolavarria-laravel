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
        Schema::create('post', function (Blueprint $table) {
            $table->bigIncrements('post_id');
            $table->string('title', 150);
            $table->unsignedBigInteger('value');
            $table->unsignedBigInteger('expenses');
            $table->unsignedBigInteger('property_id');
            $table->foreign('property_id')->references('property_id')->on('property');
            $table->unsignedBigInteger('renter_id');
            $table->foreign('renter_id')->references('renter_id')->on('renter');
            $table->boolean('featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post');
    }
};
