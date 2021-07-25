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
            $table->string('roll_number', 20)->unique();
            $table->string('name', 50);
            $table->string('email', 50)->unique();
            $table->unsignedSmallInteger('department');
            $table->unsignedSmallInteger('batch');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('department')->references('id')->on('departments')
                ->onUpdate('no action')->onDelete('no action');
            $table->foreign('batch')->references('id')->on('batches')
                ->onUpdate('no action')->onDelete('no action');
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
