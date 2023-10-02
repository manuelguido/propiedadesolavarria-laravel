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
        Schema::create('property', function (Blueprint $table) {
            $table->bigIncrements('property_id');
            $table->string('name', 150);
            $table->unsignedBigInteger('renter_id');
            $table->foreign('renter_id')->references('renter_id')->on('renter');
            $table->unsignedSmallInteger('enviroments');
            $table->unsignedSmallInteger('bathrooms');
            $table->unsignedSmallInteger('bedrooms');
            $table->unsignedSmallInteger('garages');
            $table->unsignedInteger('total_surface');
            $table->unsignedInteger('covered_surface');
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
        Schema::dropIfExists('property');
    }
};
