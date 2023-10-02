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
        Schema::table('property', function (Blueprint $table) {
            $table->unsignedTinyInteger('surface_measurement_type_id');
            $table->foreign('surface_measurement_type_id')->references('surface_measurement_type_id')->on('surface_measurement_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->dropForeign(['surface_measurement_type_id']);
            $table->dropColumn('surface_measurement_type_id');
        });
    }
};
