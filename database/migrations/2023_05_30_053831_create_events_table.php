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
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('event_name');
            $table->date('event_date');
            $table->time('event_time');
            $table->string('event_location');
            $table->integer('event_price');
            $table->integer('event_ammountticket');
            $table->text('event_description');
            $table->string('event_nameaccount');
            $table->string('event_accountnumber');
            $table->string('event_bankname');
            $table->string('event_verification');
            $table->string('event_thumbnail')->nullable();
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
        Schema::dropIfExists('events');
    }
};
