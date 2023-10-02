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
        Schema::table('post', function (Blueprint $table) {
            $table->unsignedTinyInteger('rental_type_id');
            $table->foreign('rental_type_id')->references('rental_type_id')->on('rental_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post', function (Blueprint $table) {
            $table->dropForeign(['rental_type_id']);
            $table->dropColumn('rental_type_id');
        });
    }
};
