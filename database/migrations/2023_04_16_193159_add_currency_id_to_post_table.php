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
            $table->unsignedTinyInteger('value_currency_id');
            $table->foreign('value_currency_id')->references('currency_id')->on('currency');
            $table->unsignedTinyInteger('expenses_currency_id');
            $table->foreign('expenses_currency_id')->references('currency_id')->on('currency');
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
            $table->dropForeign(['value_currency_id']);
            $table->dropColumn('value_currency_id');
            $table->dropForeign(['expenses_currency_id']);
            $table->dropColumn('expenses_currency_id');
        });
    }
};
