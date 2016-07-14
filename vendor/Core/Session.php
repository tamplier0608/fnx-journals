<?php

namespace Core;

use Core\Bag\ParameterBagInterface;

/**
 * Class Session
 * @package vendor\Core
 */
class Session implements ParameterBagInterface
{
    const SESSION_DEFAULT_STORAGE_KEY = 'app';
    protected static $flashBag;
    protected $namespace;

    public function __construct($nameSpace = '')
    {
        $this->namespace = !empty($nameSpace) ? $nameSpace : self::SESSION_DEFAULT_STORAGE_KEY;

        if (!isset($_SESSION[$this->namespace])) {
            $_SESSION[$this->namespace] = array();
        }
    }

    public static function start()
    {
        session_start();
    }

    public static function getId()
    {
        session_id();
    }

    public static function setId($id)
    {
        session_id($id);
    }

    public static function destroy()
    {
        session_destroy();
    }

    public function getFlashBag()
    {
        if (null == self::$flashBag) {
            self::$flashBag = new FlashBag($this);
        }
        return self::$flashBag;
    }

    public function get($key, $default = null)
    {
        if (false === $this->isActive()) {
            return array();
        }

        return array_key_exists($key, $_SESSION[$this->namespace])
            ? $_SESSION[$this->namespace][$key]
            : $default;
    }

    public function isActive()
    {
        return $this->getStatus() === PHP_SESSION_ACTIVE;
    }

    public function getStatus()
    {
        return session_status();
    }

    public function set($key, $value)
    {
        $_SESSION[$this->namespace][$key] = $value;
    }

    public function remove($key)
    {
        unset($_SESSION[$this->namespace][$key]);
    }

    public function all()
    {
        if (false === $this->isActive()) {
            return array();
        }
        return $_SESSION[$this->namespace];
    }

    public function has($key) {
        return isset($_SESSION[$this->namespace][$key]);
    }
}