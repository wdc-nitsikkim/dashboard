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
            $table->unsignedMediumInteger('subject_id');
            $table->unsignedDecimal('score')->nullable(false);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['result_type_id', 'student_id']);
            $table->foreign('result_type_id')->references('id')
                ->on('result_types')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('student_id')->references('id')->on('students')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')
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
        Schema::dropIfExists('results');
    }
}
