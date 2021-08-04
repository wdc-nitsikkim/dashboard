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
            $table->string('designation')->nullable(true)->default(null);
            $table->text('academic_qualifications')->nullable(true)->default(null);
            $table->text('areas_of_interest')->nullable(true)->default(null);
            $table->text('work_experience')->nullable(true)->default(null);
            $table->text('teachings')->nullable(true)->default(null);
            $table->text('office_address')->nullable(true)->default(null);
            $table->longText('publications')->nullable(true)->default(null);

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
