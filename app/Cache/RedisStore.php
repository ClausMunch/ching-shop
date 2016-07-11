<?php

namespace ChingShop\Cache;

use Illuminate\Cache\RedisStore as IlluminateRedisStore;

/**
 * Class RedisStore.
 */
class RedisStore extends IlluminateRedisStore
{
    /**
     * @param array|string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        $value = parent::get($key);

        if ($value === false) {
            return null;
        }

        return $value;
    }
}
