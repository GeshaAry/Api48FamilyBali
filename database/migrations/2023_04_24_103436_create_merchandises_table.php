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
        Schema::create('merchandises', function (Blueprint $table) {
            $table->id('merchandise_id');
            $table->bigInteger('merchandisectg_id');
            $table->string('merchandise_name');
            $table->string('merchandise_picture')->nullable();
            $table->text('merchandise_description');
            $table->string('merchandise_nameaccount');
            $table->string('merchandise_accountnumber');
            $table->string('merchandise_bankname');
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
        Schema::dropIfExists('merchandises');
    }
};
