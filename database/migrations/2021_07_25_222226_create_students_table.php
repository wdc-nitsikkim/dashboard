<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('roll_number', 20)->nullable(false);
            $table->string('name', 50)->nullable(false);
            $table->string('email', 50)->nullable(false);
            $table->unsignedSmallInteger('department_id');
            $table->unsignedSmallInteger('batch_id');

            $table->timestamps();
            $table->softDeletes();

            $table->unique('roll_number');
            $table->unique('email');
            $table->foreign('department_id')->references('id')->on('departments')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('batch_id')->references('id')->on('batches')
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
        Schema::dropIfExists('students');
    }
}
