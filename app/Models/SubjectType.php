<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Subject;

class SubjectType extends Model {
    protected $table = 'subject_types';
    public $timestamps = false;

    /**
     * Defines one-to-many relationship
     */
    public function subject() {
        return $this->hasMany(Subject::class, 'subject_type_id');
    }
}
