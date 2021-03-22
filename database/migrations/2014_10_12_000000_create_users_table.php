<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 55);
            $table->string('email', 55)->unique();
            $table->string('phone', 13)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profile')->default('avatar.png');
            $table->string('device_token', 64)->nullable();
            $table->string('special_needs')->nullable(); //deaf/blind/other
            $table->string('gender', 55)->nullable();//male/female/Mixed gender/other
            $table->string('address', 55)->nullable();
            $table->string('county', 30)->nullable();
            $table->string('constituency', 55)->nullable();
            $table->string('ward', 55)->nullable();
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_teacher')->default(false);
            $table->boolean('is_student')->default(true);
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
