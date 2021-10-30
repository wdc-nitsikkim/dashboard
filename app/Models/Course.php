<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Batch;
use App\Models\Subject;

class Course extends Model
{
    protected $table = 'courses';
    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'code';
    }

    /**
     * Defines one-to-many relationship
     */
    public function batches()
    {
        return $this->hasMany(Batch::class, 'course_id');
    }

    /**
     * Defines one-to-many relationship
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'course_id');
    }
}
