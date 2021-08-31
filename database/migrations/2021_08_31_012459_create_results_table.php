<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('result_type_id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('registered_subject_id');
            $table->unsignedDecimal('score')->nullable(false);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['result_type_id', 'student_id', 'registered_subject_id']);
            $table->foreign('result_type_id')->references('id')
                ->on('result_types')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('student_id')->references('id')->on('students')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('registered_subject_id')->references('id')->on('registered_subjects')
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
        Schema::dropIfExists('results');
    }
}
