<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAccessDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_access_departments', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedSmallInteger('department_id');

            $table->timestamps();

            $table->unique(['user_id', 'department_id']);
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_access_departments');
    }
}
