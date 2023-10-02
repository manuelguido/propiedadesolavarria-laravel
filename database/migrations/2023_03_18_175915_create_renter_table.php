<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renter', function (Blueprint $table) {
            $table->bigIncrements('renter_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('user');
            $table->unsignedBigInteger('phone');
            $table->unsignedBigInteger('whatsapp_phone');
            $table->string('estate_agent', 100);
            $table->string('commercial_email', 150);
            $table->string('address', 100);
            $table->string('image')->nullable()->default(NULL);
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
        Schema::dropIfExists('renter');
    }
};
