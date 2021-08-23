<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('department_id');
            $table->unsignedTinyInteger('course_id');
            $table->unsignedTinyInteger('semester_id');
            $table->string('code', 8)->nullable(false);
            $table->string('name')->nullable(false);
            $table->tinyInteger('credit')->nullable(false);

            $table->timestamps();
            $table->softDeletes();

            $table->unique('code');
            $table->foreign('department_id')->references('id')->on('departments')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('course_id')->references('id')->on('courses')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('semester_id')->references('id')->on('semesters')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjects');
    }
}
