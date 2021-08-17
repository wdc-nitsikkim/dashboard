<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Student;
use App\Models\Subject;
use App\Traits\GlobalAccessors;

class Result extends Model {
    use softDeletes;
    use GobalAccessors;

    protected $table = 'results';

    /**
     * The attributes that are mass-assignable
     */
    protected $fillable = [
        'student_id', 'subject_id', 'score'
    ];

    /**
     * Defines many-to-many relationship
     */
    public function student() {
        return $this->belongsTo(Student::class, 'student_id')->withDefault();
    }

    /**
     * Defines many-to-one relationship
     */
    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id')->withDefault();
    }
}
