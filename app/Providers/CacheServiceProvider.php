<?php

namespace ChingShop\Providers;

use Cache;
use ChingShop\Cache\RedisStore;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Class CacheServiceProvider
 *
 * @package ChingShop\Providers
 */
class CacheServiceProvider extends ServiceProvider
{
    /**
     * Use customised Redis database.
     */
    public function boot()
    {
        Cache::extend(
            'redis', function (Application $app) {
                return Cache::repository(
                    new RedisStore(
                        $app['redis'],
                        config('cache.prefix'),
                        config('cache.stores.redis.connection')
                    )
                );
            }
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}
