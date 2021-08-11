<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedInteger('user_id');
            $table->enum('role', [
                    'root', 'admin', 'office', 'tnp', 'ecell',
                    'hod', 'faculty', 'staff', 'student'
                ])->nullable(false);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'role']);
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('user_roles');
    }
}
