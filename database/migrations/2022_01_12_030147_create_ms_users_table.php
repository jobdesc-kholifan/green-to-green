<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->bigInteger('gender_id')->nullable();
            $table->string('place_of_birth', 25)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->string('user_name', 100)->nullable();
            $table->text('user_password')->nullable();
            $table->bigInteger('role_id');
            $table->bigInteger('status_id');

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
        Schema::dropIfExists('ms_users');
    }
}
