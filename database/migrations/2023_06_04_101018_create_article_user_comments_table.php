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
        Schema::create('article_user_comments', function (Blueprint $table) {
            $table->id('articleusercomment_id');
            $table->bigInteger('articleuser_id');
            $table->bigInteger('user_id');
            $table->text('articleuser_comment');
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
        Schema::dropIfExists('article_user_comments');
    }
};
