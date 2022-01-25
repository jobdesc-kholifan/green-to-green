<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsAchievementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_achievement', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->integer('sequence');
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->bigInteger('status_id')->unsigned();

            $table->timestamps();

            $table->index(['status_id']);

            $table->foreign('status_id')
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
        Schema::dropIfExists('ms_achievement');
    }
}
