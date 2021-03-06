<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedTinyInteger('course_id');
            $table->string('code', 10)->nullable(false);
            $table->string('name', 100)->nullable(false);
            $table->integer('start_year')->nullable(false);

            $table->timestamps();
            $table->softDeletes();

            $table->unique('code');
            $table->unique(['course_id', 'start_year']);
            $table->foreign('course_id')->references('id')->on('courses')
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
        Schema::dropIfExists('batches');
    }
}
