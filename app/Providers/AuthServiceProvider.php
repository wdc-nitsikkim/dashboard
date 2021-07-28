<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Department;
use App\Models\HomepageNotification;
use App\Policies\DepartmentPolicy;
use App\Policies\HomepageNotificationPolicy as NotificationPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        HomepageNotification::class => NotificationPolicy::class,
        Department::class => DepartmentPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function($user, $ability) {
            return $user->role === 'root' ? true : null;
        });
    }
}
