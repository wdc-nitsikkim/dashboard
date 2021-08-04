<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', [
                    'faculty', 'staff', 'other'
                ])->nullable(false);
            $table->unsignedSmallInteger('department_id');
            $table->string('name')->nullable(false);
            $table->string('mobile')->nullable(false);
            $table->string('email')->nullable(false);
            $table->string('designation');
            $table->text('academic_qualifications');
            $table->text('areas_of_interest');
            $table->text('work_experience');
            $table->text('teachings');
            $table->text('office_address');
            $table->longText('publications');

            $table->timestamps();
            $table->softDeletes();

            $table->unique('email');
            $table->unique('mobile');
            $table->foreign('department_id')->references('id')->on('departments')
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
        Schema::dropIfExists('profiles');
    }
}
