<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\GlobalMutators;
use App\Traits\GlobalAccessors;

class HomepageNotification extends Model {
    use SoftDeletes;
    use GlobalMutators, GlobalAccessors;

    protected $table = 'homepage_notifications';
    protected $dates = ['deleted_at'];

    /**
     * attributes that cannot be mass-assigned
     *
     * @var array
     */
    protected $guarded = ['id'];
}
