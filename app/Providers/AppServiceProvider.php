<?php

namespace App\Providers;

use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('isSuperAdmin', function ($user) {
            return $user->role->nama == 'SuperAdmin';
        });

        Gate::define('isAdmin', function (User $user) {
            return $user->role->nama == 'Admin';
        });

        Gate::define('superAdminAndAdmin', function (User $user) {
            return ($user->role->nama == 'SuperAdmin' || $user->role->nama == 'Admin');
        });

        Gate::define('isPJ', function (User $user) {
            return $user->role->nama == 'PJ';
        });

        Gate::define('PJ-Dashboard', function (User $user, Kegiatan $kegiatan) {
            return $user->id === $kegiatan->user_id;
        });

        Gate::define('update-kegiatan', function (User $user, Kegiatan $kegiatan) {
            return ($user->role->nama == 'SuperAdmin' || $user->role->nama == 'Admin' || $user->id == $kegiatan->user_id);
        });

        Gate::define('delete-kegiatan', function (User $user) {
            return $user->role->nama == 'SuperAdmin' || $user->role->nama == 'Admin';
        });
    }
}
