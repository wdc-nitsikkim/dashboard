<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Result;

class ResultType extends Model {
    protected $table = 'result_types';
    public $timestamps = false;

    /**
     * Defines one-to-many relationship
     */
    public function result() {
        return $this->hasMany(Result::class, 'student_id');
    }
}
