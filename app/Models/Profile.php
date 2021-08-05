<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\UserProfileLink;
use App\Traits\GlobalMutators;
use App\Traits\GlobalAccessors;

class Profile extends Model {
    use softDeletes;
    use GlobalMutators, GlobalAccessors;

    protected $table = 'profiles';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'type', 'department_id', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * Defines one-to-one relationship
     */
    public function userLink() {
        return $this->hasOne(UserProfileLink::class, 'profile_id');
    }

    /**
     * Set designation
     *
     * @param string $value
     * @return void
     */
    public function setDesignationAttribute($value) {
        $this->attributes['designation'] = ucwords($value);
    }
}
