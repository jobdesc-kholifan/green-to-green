<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsrAchievementTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usr_achievement_task', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_achievement_id')->unsigned();
            $table->bigInteger('task_type_id')->unsigned();
            $table->integer('points');
            $table->text('payload')->nullable();

            $table->timestamps();

            $table->index(['user_achievement_id']);
            $table->index('task_type_id', 'usr_task_type_id');

            $table->foreign('user_achievement_id')
                ->references('id')->on('usr_achievement')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('task_type_id')
                ->references('id')->on('ms_configs')
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
        Schema::dropIfExists('usr_achievement_task');
    }
}
