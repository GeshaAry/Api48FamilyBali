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
        Schema::create('transaction_merchandises', function (Blueprint $table) {
            $table->id('merchandisetns_id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('admin_id')->nullable();
            $table->dateTime('merchandisetns_datebuy')->nullable();
            $table->integer('merchandisetns_totalprice')->nullable();
            $table->string('merchandisetns_proofpayment')->nullable();
            $table->string('merchandisetns_status')->nullable();
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
        Schema::dropIfExists('transaction_merchandises');
    }
};
