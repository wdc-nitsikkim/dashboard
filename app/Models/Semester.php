<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Subject;

class Semester extends Model {
    protected $table = 'semesters';
    public $timestamps = false;

    /**
     * Defines one-to-many relationship
     */
    public function subjects() {
        return $this->hasMany(Subject::class, 'subject_id');
    }
}
