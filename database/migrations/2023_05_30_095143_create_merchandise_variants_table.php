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
        Schema::create('merchandise_variants', function (Blueprint $table) {
            $table->id('merchandisevar_id');
            $table->bigInteger('merchandise_id');
            $table->string('merchandisevar_size');
            $table->integer('merchandisevar_price');
            $table->integer('merchandisevar_stock');
            $table->softDeletes();
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
        Schema::dropIfExists('merchandise_variants');
    }
};
