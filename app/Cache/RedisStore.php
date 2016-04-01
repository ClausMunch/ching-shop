<?php

namespace ChingShop\Cache;

use Illuminate\Cache\RedisStore as IlluminateRedisStore;

/**
 * Class RedisStore
 *
 * @package ChingShop\Cache
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
