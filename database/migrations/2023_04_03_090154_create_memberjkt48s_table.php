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
        Schema::create('memberjkt48s', function (Blueprint $table) {
            $table->id('member_id');
            $table->string('member_name');
            $table->string('member_jiko');
            $table->integer('member_show');
            $table->integer('member_gen');
            $table->string('member_status');
            $table->string('member_birthdate');
            $table->string('member_blood');
            $table->string('member_horoskop');
            $table->string('member_height');
            $table->string('member_instagram')->nullable();
            $table->string('member_twitter')->nullable();
            $table->string('member_tiktok')->nullable();
            $table->string('member_showroom')->nullable();
            $table->string('member_picture')->nullable();
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
        Schema::dropIfExists('memberjkt48s');
    }
};
