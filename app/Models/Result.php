<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Student;
use App\Models\Subject;
use App\Models\ResultType;
use App\Traits\GlobalAccessors;

class Result extends Model {
    use SoftDeletes;
    use GlobalAccessors;

    protected $table = 'results';

    /**
     * The attributes that are mass-assignable
     */
    protected $fillable = [
        'student_id', 'subject_id', 'score'
    ];

    /**
     * Defines many-to-one relationship
     */
    public function resultType() {
        return $this->belongsTo(ResultType::class, 'result_type_id')->withDefault();
    }

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
