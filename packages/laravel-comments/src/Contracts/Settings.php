<?php

namespace Hazzard\Comments\Contracts;

interface Settings
{
    /**
     * Determine if the given setting value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key);

    /**
     * Set a setting value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function set($key, $value);

    /**
     * Get the specified setting value.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Forget a setting value.
     *
     * @param  string  $key
     * @return void
     */
    public function forget($key);
}
