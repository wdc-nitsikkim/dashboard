<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Course;
use App\Models\Student;
use App\Traits\GlobalMutators;
use App\Traits\GlobalAccessors;

class Batch extends Model {
    use SoftDeletes;
    use GlobalMutators, GlobalAccessors;

    protected $table = 'batches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id', 'code', 'name', 'start_year'
    ];

    /**
     * Match route parameter of this model to specified string
     * instead of default 'id'
     *
     * @return string
     */
    public function getRouteKeyName() {
        return 'code';
    }

    /**
     * Defines many-to-one relationship
     */
    public function course() {
        return $this->belongsTo(Course::class, 'course_id')->withDefault();
    }

    /**
     * Defines one-to-many relationship
     */
    public function students() {
        return $this->hasMany(Student::class, 'batch_id');
    }
}
