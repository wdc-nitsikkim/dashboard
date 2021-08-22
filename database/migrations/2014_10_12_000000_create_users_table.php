<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name');
            $table->string('mobile')->nullable(false);
            $table->string('email')->nullable(false);
            $table->timestamp('email_verified_at')->nullable(true)->default(null);
            $table->string('email_token')->nullable(true)->default(null);
            $table->string('image')->nullable(true)->default(null);
            $table->string('password');
            $table->rememberToken();

            $table->timestamps();
            $table->softDeletes();

            $table->unique('mobile');
            $table->unique('email');
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
