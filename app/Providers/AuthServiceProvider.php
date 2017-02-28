<?php

namespace ChingShop\Providers;

use ChingShop\Modules\User\Model\User;
use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider.
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'ChingShop\Model' => 'ChingShop\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define(
            'administer',
            function (User $user) {
                return $user->roles->contains('name', 'admin');
            }
        );
    }
}
