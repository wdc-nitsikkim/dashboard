<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Batch;
use App\Models\Result;
use App\Models\Department;
use App\Traits\GlobalMutators;
use App\Traits\GlobalAccessors;

class Student extends Model {
    use SoftDeletes;
    use GlobalMutators, GlobalAccessors;

    protected $table = 'students';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'roll_number', 'name', 'email', 'department_id', 'batch_id'
    ];

    /**
     * Defines one-to-many relationship
     */
    public function result() {
        return $this->hasMany(Result::class, 'student_id');
    }

    /**
     * Defines many-to-one relationship
     */
    public function department() {
        return $this->belongsTo(Department::class, 'department_id')->withDefault();
    }

    /**
     * Defines many-to-one relationship
     */
    public function batch() {
        return $this->belongsTo(Batch::class, 'batch_id')->withDefault();
    }

    /**
     * Model mutator
     * Set the students roll_number
     *
     * @param string $value
     * @return void
     */
    public function setRollNumberAttribute($value) {
        $this->attributes['roll_number'] = strtoupper($value);
    }
}
