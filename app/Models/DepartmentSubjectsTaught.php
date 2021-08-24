<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Subject;
use App\Models\Department;

class DepartmentSubjectsTaught extends Model {
    protected $table = 'department_subjects_taught';
    public $timestamps = false;

    /**
     * The attributes that are mass-assignable
     */
    protected $fillable = ['department_id', 'subject_id'];

    public function department() {
        return $this->belongsTo(Department::class, 'department_id')->withDefault();
    }

    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id')->withDefault();
    }
}
