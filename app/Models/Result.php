<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Student;
use App\Models\ResultType;
use App\Models\RegisteredSubject;
use App\Traits\GlobalAccessors;

class Result extends Model {
    use SoftDeletes;
    use GlobalAccessors;

    protected $table = 'results';

    /**
     * The attributes that are mass-assignable
     */
    protected $fillable = [
        'result_type_id', 'student_id', 'subject_id', 'score'
    ];

    /**
     * Cast attributes to primitive data types
     */
    protected $casts = [
        'score' => 'float'
    ];

    /**
     * Relationships which should be eager loaded automatically
     */
    protected $with = ['resultType'];

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
        return $this->belongsTo(RegisteredSubject::class, 'registered_subject_id')->withDefault();
    }

    /**
     * Custom dynamic accessor of model
     */
    public function getPercentageAttribute() {
        return round(($this->score / $this->resultType->max_marks) * 100, 2);
    }
}
