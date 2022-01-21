<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrOrderRubbishTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_order_rubbish', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->integer('qty');
            $table->bigInteger('status_id')->unsigned();

            $table->timestamps();

            $table->index(['order_id', 'category_id', 'status_id']);

            $table->foreign('order_id')
                ->references('id')->on('tr_order')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('status_id')
                ->references('id')->on('ms_configs')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->foreign('category_id')
                ->references('id')->on('ms_configs')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_order_rubbish');
    }
}
