<?php

namespace Hazzard\Comments;

use Exception;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Cache\Repository as CacheContract;

class Settings implements Contracts\Settings
{
    /**
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $conn;

    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var bool
     */
    protected $cacheEnabled = true;

    /**
     * Create new database repository.
     *
     * @param  \Illuminate\Database\ConnectionInterface $conn
     * @param  \Illuminate\Contracts\Cache\Repository $cache
     * @param  string $table
     * @return void
     */
    public function __construct(ConnectionInterface $conn, CacheContract $cache, $table)
    {
        $this->conn = $conn;
        $this->cache = $cache;
        $this->table = $table;
    }

    /**
     * Determine if the given setting value exists.
     *
     * @param  string $key
     * @return bool
     */
    public function has($key)
    {
        return $this->table()->where('key', '=', $key)->count() > 0;
    }

    /**
     * Set a setting value.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function set($key, $value = null)
    {
        $value = serialize($value);

        try {
            $this->table()->insert(compact('key', 'value'));
        } catch (Exception $e) {
            $this->table()->where('key', '=', $key)->update(compact('value'));
        }

        if ($this->cacheEnabled) {
            $this->cache->forget($this->getKey($key));
        }
    }

    /**
     * Get the specified setting value.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = function () use ($key) {
            return $this->table()->where('key', '=', $key)->value('value');
        };

        if ($this->cacheEnabled) {
            $key = $this->getKey($key);

            $value = $this->cache->rememberForever($key, function () use ($value) {
                return $value();
            });
        } else {
            $value = $value();
        }

        if (is_null($value)) {
            $value = $default;
        } else {
            try {
                $value = unserialize($value);
            } catch (Exception $e) {
            }
        }

        return $value;
    }

    /**
     * Forget a setting value.
     *
     * @param  string $key
     * @return void
     */
    public function forget($key)
    {
        $this->table()->where('key', $key)->delete();

        if ($this->cacheEnabled) {
            $this->cache->forget($this->getKey($key));
        }
    }

    /**
     * Disable cache.
     *
     * @return void
     */
    public function disableCache()
    {
        $this->cacheEnabled = false;
    }

    /**
     * Get a query builder for the settings table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function table()
    {
        return $this->conn->table($this->table);
    }

    /**
     * Get repository cache key.
     *
     * @param  string $key
     * @return string
     */
    protected function getKey($key)
    {
        return 'comments.settings.'.$key;
    }
}
