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
        Schema::table('transaction_merchandises', function (Blueprint $table) {
            $table->bigInteger('merchandisevar_id')->nullable();
            $table->integer('merchandisetns_quantity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_merchandises', function (Blueprint $table) {
            //
        });
    }
};
