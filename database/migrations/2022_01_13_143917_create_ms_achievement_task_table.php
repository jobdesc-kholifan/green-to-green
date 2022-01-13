<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsAchievementTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_achievement_task', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('achievement_id');
            $table->unsignedBigInteger('task_type_id');
            $table->text('payload');

            $table->timestamps();

            $table->index(['achievement_id', 'task_type_id']);

            $table->foreign('achievement_id')
                ->references('id')->on('ms_achievement')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('task_type_id')
                ->references('id')->on('ms_configs')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ms_achievement_task');
    }
}
