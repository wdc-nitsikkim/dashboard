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
            $table->enum('type', ['b', 'm'])->default('b')->nullable(false);  /* b => btech, m => mtech */
            $table->string('batch', 10)->nullable(false);
            $table->string('full_name', 100)->nullable(false);
            $table->integer('start_year')->nullable(false);

            $table->timestamps();
            $table->softDeletes();

            $table->unique('batch');
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
