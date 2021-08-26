<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Student;

class StudentInfo extends Model {
    protected $table = 'students_information';
    protected $primaryKey = 'student_id';
    public $incrementing = false;

    /**
     * The attributes that are not mass-assignable
     */
    protected $guarded = [
        'student_id', 'created_at', 'updated_at'
    ];

    /**
     * Defines one-to-one relationship with Student model
     */
    public function student() {
        return $this->belongsTo(Student::class, 'student_id')->withDefault();
    }
}
