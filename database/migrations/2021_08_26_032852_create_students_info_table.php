<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_information', function (Blueprint $table) {
            $table->unsignedInteger('student_id');
            $table->enum('gender', ['male', 'female', 'other'])->nullable(false);
            $table->date('date_of_birth')->nullable(false);
            $table->string('personal_email')->nullable(true)->default(null);
            $table->string('secondary_mobile')->nullable(true)->default(null);
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-',
                'O+', 'O-'])->nullable(false);

            $table->enum('category', ['general', 'obc', 'obc (ncl)', 'sc', 'st', 'other'])
                ->nullable(false)->default('general');
            $table->enum('religion', ['hinduism', 'islam', 'christianity', 'sikhism',
                'buddhism', 'jainism', 'other'])->nullable(false)->default('hinduism');

            $table->string('fathers_name')->nullable(false);
            $table->string('fathers_mobile')->nullable(true)->default(null);
            $table->string('mothers_name')->nullable(false);
            $table->string('mothers_mobile')->nullable(true)->default(null);

            $table->unsignedDecimal('10th_score')->nullable(false);
            $table->enum('10th_marking_scheme', ['cgpa', 'percentage'])->nullable(false);
            $table->smallInteger('10th_passing_year')->nullable(false);
            $table->enum('10th_board', ['cbse', 'icse', 'other'])->nullable(false);
            $table->string('10th_board_other')->nullable(true)->default(null);
            $table->string('10th_school')->nullable(true)->default(null);
            $table->unsignedDecimal('12th_score')->nullable(false);
            $table->enum('12th_marking_scheme', ['cgpa', 'percentage'])->nullable(false);
            $table->smallInteger('12th_passing_year')->nullable(false);
            $table->enum('12th_board', ['cbse', 'icse', 'other'])->nullable(false);
            $table->string('12th_board_other')->nullable(true)->default(null);
            $table->string('12th_school')->nullable(true)->default(null);

            $table->unsignedDecimal('cgpa')->nullable(true)->default(null);
            $table->unsignedTinyInteger('till_sem')->nullable(true)->default(null);

            $table->string('image')->nullable(true)->default(null);
            $table->string('signature')->nullable(true)->default(null);
            $table->string('resume')->nullable(true)->default(null);


            $table->timestamps();
            $table->softDeletes();

            $table->primary('student_id');
            $table->foreign('student_id')->references('id')->on('students')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('till_sem')->references('id')->on('semesters')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students_information');
    }
}
