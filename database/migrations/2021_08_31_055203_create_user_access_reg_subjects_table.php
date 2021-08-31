<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAccessRegSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_access_reg_subjects', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('registered_subject_id');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'registered_subject_id']);
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('registered_subject_id')->references('id')->on('registered_subjects')
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
        Schema::dropIfExists('user_access_subjects');
    }
}
