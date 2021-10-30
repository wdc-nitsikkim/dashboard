<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Hod;
use App\Models\Student;
use App\Models\Subject;
use App\Traits\GlobalMutators;
use App\Traits\GlobalAccessors;

class Department extends Model
{
    use SoftDeletes;
    use GlobalMutators, GlobalAccessors;

    protected $table = 'departments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name'
    ];

    /**
     * Match route parameter of this model to specified string
     * instead of default 'id'
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'code';
    }

    /**
     * Defines one-to-many relationship
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'department_id');
    }

    /**
     * Defines one-to-many relationship
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'department_id');
    }

    /**
     * Defines one-to-one relationship on Hods table
     */
    public function hod()
    {
        return $this->hasOne(Hod::class, 'department_id');
    }
}
