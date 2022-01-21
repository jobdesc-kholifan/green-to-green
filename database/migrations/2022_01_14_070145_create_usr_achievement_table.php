<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsrAchievementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usr_achievement', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('achievement_id')->unsigned();
            $table->integer('points')->default(0);
            $table->integer('percentage')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'achievement_id']);

            $table->foreign('user_id')
                ->references('id')->on('ms_users')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->foreign('achievement_id')
                ->references('id')->on('ms_achievement')
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usr_achievement');
    }
}
