<?php

namespace ChingShop\Providers;

use ChingShop\Modules\User\Model\User;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as Provider;

/**
 * Class AuthServiceProvider.
 */
class AuthServiceProvider extends Provider
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
     * Register any application authentication / authorization services.
     *
     * @param \Illuminate\Contracts\Auth\Access\Gate $gate
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define(
            'administer',
            function (User $user) {
                return $user->roles->contains('name', 'admin');
            }
        );
    }
}
