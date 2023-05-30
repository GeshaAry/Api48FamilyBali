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
        Schema::create('transaction_events', function (Blueprint $table) {
            $table->id('transactionevent_id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('admin_id')->nullable();
            $table->bigInteger('event_id')->nullable();
            $table->dateTime('transactionevent_datebuy')->nullable();
            $table->integer('transactionevent_quantity')->nullable();
            $table->integer('transactionevent_totalprice')->nullable();
            $table->string('transactionevent_status')->nullable();
            $table->string('transactionevent_proofpayment')->nullable();
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
        Schema::dropIfExists('transaction_events');
    }
};
