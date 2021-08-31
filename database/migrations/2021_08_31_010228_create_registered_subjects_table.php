<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisteredSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registered_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('department_id');
            $table->unsignedSmallInteger('batch_id');
            $table->unsignedTinyInteger('semester_id');
            $table->unsignedMediumInteger('subject_id');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['department_id', 'semester_id', 'batch_id', 'subject_id'], 'table_unique_key_1');
            $table->foreign('department_id')->references('id')->on('departments')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('batch_id')->references('id')->on('batches')
                    ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('semester_id')->references('id')->on('semesters')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('subject_id')->references('id')->on('subjects')
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
        Schema::dropIfExists('registered_subjects');
    }
}
