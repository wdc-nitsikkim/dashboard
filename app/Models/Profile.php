<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\UserProfileLink;
use App\Traits\GlobalMutators;
use App\Traits\GlobalAccessors;

class Profile extends Model {
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
}
