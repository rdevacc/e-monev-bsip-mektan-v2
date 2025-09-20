<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Activity;
use App\Models\Kegiatan;
use App\Models\User;
use App\Policies\ActivityPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Activity::class => ActivityPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('isSuperAdmin', function($user) {
            return $user->role->name === 'SuperAdmin' || $user->role->id == 1;
        });

        Gate::define('isAdmin', function($user) {
            return $user->role->id == 2 || $user->role->name === 'SuperAdmin' || $user->role->id == 1;
        });
        
        Gate::define('isPJ', function($user) {
            return $user->role->id == 3 || $user->role->name === 'PJ' || $user->role->id == 2 || $user->role->name === 'Admin' || $user->role->id == 1 || $user->role->name === 'SuperAdmin';
        });

        Gate::define('deleteSuperAdmin', function ($user, $targetUser) {
            // Hanya super admin bisa hapus super admin
            return $user->role->id == 1 && $targetUser->role !== 'SuperAdmin';
        });
    }
}
