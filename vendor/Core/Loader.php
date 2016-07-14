<?php

namespace Core;

class Loader
{
    /**
     * Registered paths
     *
     * @var array
     */
    protected static $paths = array();

    /**
     * Loads of class
     *
     * @param  string $class Class name
     * @return boolean True if class waqs found or false if not
     */
    public static function load($class)
    {

        $class = str_replace('\\', '/', $class) . '.php';

        if (file_exists($class)) {
            require_once $class;
        } else {
            foreach (self::$paths as $path) {
                $path = rtrim($path, '/');

                if (file_exists($path . '/' . $class)) {
                    require_once $path . '/' . $class;
                }
            }
        }
        return false;
    }

    /**
     * Register path for autoload
     *
     * @param  string $path Path to folder with class
     * @return $this
     */
    public static function registerPath($path)
    {
        if (!in_array($path, self::$paths)) {
            self::$paths[] = $path;
        }
    }

    /**
     * Gets all paths for autoload
     *
     * @return array
     */
    public function getPaths()
    {
        return self::paths;
    }
}