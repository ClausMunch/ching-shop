<?php

namespace ChingShop\Providers;

use ChingShop\Cache\RedisStore;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Class CacheServiceProvider.
 */
class CacheServiceProvider extends ServiceProvider
{
    /**
     * Use customised Redis database.
     */
    public function boot()
    {
        $this->cache()->extend(
            'redis', function (Application $app) {
                return $this->cache()->repository(
                    new RedisStore(
                        $app->make('redis'),
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

    /**
     * @return CacheManager
     */
    private function cache()
    {
        return $this->app->make('cache');
    }
}
