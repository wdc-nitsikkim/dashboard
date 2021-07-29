<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_role_permissions', function (Blueprint $table) {
            $table->unsignedSmallInteger('role_id');
            $table->enum('permission', ['c', 'r', 'u', 'd'])->default('r')->nullable(false);
            /* c => create, r => read, u => update, d => delete */

            $table->timestamps();

            $table->unique(['role_id', 'permission']);
            $table->foreign('role_id')->references('id')->on('user_roles')
                ->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_permissions');
    }
}