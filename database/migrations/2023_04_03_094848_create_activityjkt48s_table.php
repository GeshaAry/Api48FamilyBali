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
        Schema::create('activityjkt48s', function (Blueprint $table) {
            $table->id('activity_id');
            $table->dateTime('activity_date');
            $table->string('activity_thumbnail')->nullable();
            $table->string('activity_title');
            $table->text('activty_description');
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
        Schema::dropIfExists('activityjkt48s');
    }
};
