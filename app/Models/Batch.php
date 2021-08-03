<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Student;
use App\Traits\GlobalMutators;
use App\Traits\GlobalAccessors;

class Batch extends Model {
    use softDeletes;
    use GlobalMutators, GlobalAccessors;

    protected $table = 'batches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'code', 'name', 'start_year'
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
     * Defines one-to-many relationship
     */
    public function students() {
        return $this->hasMany(Student::class, 'batch_id');
    }
}
