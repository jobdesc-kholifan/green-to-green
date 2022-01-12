<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_configs', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 30)->nullable();
            $table->string('config_name', 100);
            $table->bigInteger('parent_id')->nullable();
            $table->integer('sequence')->nullable();
            $table->text('payload')->nullable();

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
        Schema::dropIfExists('ms_configs');
    }
}
