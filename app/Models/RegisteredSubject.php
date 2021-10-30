<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Batch;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\Department;

class RegisteredSubject extends Model
{
    use SoftDeletes;

    protected $table = 'registered_subjects';

    /**
     * Relationships which should be eager loaded automatically
     */
    protected $with = ['semester', 'batch', 'subject.department', 'subject.type'];

    /**
     * Defines many-to-one relationship
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id')->withDefault();
    }

    /**
     * Defines many-to-one relationship
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id')->withDefault();
    }

    /**
     * Defines many-to-one relationship
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id')->withDefault();
    }

    /**
     * Defines many-to-one relationship
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id')->withDefault();
    }

    /**
     * Custom dynamic accessor of model
     */
    public function getSubjectCodeAttribute()
    {
        $code = mb_substr($this->subject->department->code, 0, 2) . $this->batch->course_id
            . $this->semester->id . $this->subject->type->id . $this->subject->code;
        return strtoupper($code);
    }
}
