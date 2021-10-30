<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Course;
use App\Models\Department;
use App\Models\SubjectType;
use App\Traits\GlobalMutators;

class Subject extends Model
{
    use SoftDeletes;
    use GlobalMutators;

    protected $table = 'subjects';

    /**
     * Attributes that are not mass-assignable
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

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
    public function type()
    {
        return $this->belongsTo(SubjectType::class, 'subject_type_id')->withDefault();
    }

    /**
     * Defines many-to-one relationship
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id')->withDefault();
    }

    /**
     * Set name
     * Override trait mutator
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
    }
}
