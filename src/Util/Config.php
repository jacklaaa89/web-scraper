<?php

namespace Example\Util;

use Exception;

/**
 * Class Config
 *
 * Manages the configuration of the application.
 *
 * @package Example\Util
 */
class Config
{
    /** @var array */
    private $config = [];

    /**
     * Load configuration(s) from file.
     *
     * When specifying multiple files, duplicate keys from later files
     * will overwrite the values in earlier files.
     *
     * @see http://php.net/manual/en/function.array-replace-recursive.php
     *
     * @param string|array $files The files to load configuration from
     *
     * @throws Exception
     */
    public function __construct($files)
    {
        $files = is_array($files) ? $files : [$files];

        foreach ($files as $file) {
            $config = require $file;

            if (!is_array($config)) {
                throw new Exception('Configuration files must return a PHP associative array');
            }

            $this->config = array_replace_recursive($this->config, $config);
        }
    }

    /**
     * Get a specific entry from the configuration.
     *
     * Uses a dot-separated string to navigate the configuration array.
     *
     * e.g.
     * $config = [
     *     foo => [
     *         'bar' => [
     *             'baz' => '1234'
     *         ]
     *     ],
     *     bar.baz => 'abcd'
     * ]
     *
     * get('foo')         -> ['foo' => ['baz' => '1234']]
     * get('foo.bar')     -> ['baz' => '1234']
     * get('foo.bar.baz') -> '1234'
     * get('bar.baz')     -> 'abcd'
     *
     * @param string $key The key of the value to return
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->find($key, $this->config);
    }

    /**
     * Recursively navigate the config array until the
     * value is found or the search is exhausted.
     *
     * @param string $key    The key to find
     * @param array  $config The array to search
     *
     * @return mixed
     */
    protected final function find($key, $config)
    {
        if (array_key_exists($key, $config)) {
            return $config[$key];
        }

        if (strpos($key, '.') !== false) {
            // split the first part of the key off
            list($node, $remainingKey) = explode('.', $key, 2);

            // navigate deeper into the array
            if (array_key_exists($node, $config)) {
                $value = $this->find($remainingKey, $config[$node]);

                if (!is_null($value)) {
                    return $value;
                }
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->config;
    }
}
