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
        Schema::table('property', function (Blueprint $table) {
            $table->unsignedTinyInteger('antiquity_type_id');
            $table->foreign('antiquity_type_id')->references('antiquity_type_id')->on('antiquity_type');
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
            $table->dropForeign(['antiquity_type_id']);
            $table->dropColumn('antiquity_type_id');
        });
    }
};
