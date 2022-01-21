<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsrFollowerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usr_follower', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('user_follower_id')->unsigned();

            $table->timestamps();

            $table->index(['user_id', 'user_follower_id']);

            $table->foreign('user_id')
                ->references('id')->on('ms_users')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->foreign('user_follower_id')
                ->references('id')->on('ms_users')
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
        Schema::dropIfExists('usr_follower');
    }
}
