<?php

namespace App\Providers;

use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //         'App\Build' => 'App\Policies\BuildPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        /**
         * Determine if the current user can access the admin panel.
         *
         * @param  User  $user
         * @return bool
         */
        \Gate::define('moderate-comments', function (User $user) {
            return $user->can('manage-comments');
        });
    }
}
