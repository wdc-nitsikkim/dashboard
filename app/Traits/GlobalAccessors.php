<?php

namespace App\Traits;

use App\CustomHelper;

/**
 * Accessor functions to be applied on common attributes
 */
trait GlobalAccessors {
    /**
     * Model accessor
     * Modify created_at attribute before accessing
     *
     * @param string $value
     * @return string
     */
    public function getCreatedAtAttribute($value) {
        return CustomHelper::utcToAppTimezone($value);
    }

    /**
     * Model accessor
     * Modify updated_at attribute before accessing
     *
     * @param string $value
     * @return string
     */
    public function getUpdatedAtAttribute($value) {
        return CustomHelper::utcToAppTimezone($value);
    }
}
