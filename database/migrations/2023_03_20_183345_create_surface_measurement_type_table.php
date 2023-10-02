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
        Schema::create('surface_measurement_type', function (Blueprint $table) {
            $table->tinyIncrements('surface_measurement_type_id');
            $table->string('name', 50)->unique();
            $table->string('short_name', 20)->unique();
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
        Schema::dropIfExists('surface_measurement_type');
    }
};
