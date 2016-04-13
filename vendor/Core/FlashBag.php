<?php

namespace Core;

use Core\Bag\ParameterBagInterface;

/**
 * Class FlashBag
 * @package vendor\Core
 */
class FlashBag implements ParameterBagInterface
{
    const FLASHBAG_SESSION_KEY = 'flashes';

    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;

        if ($session->isActive() && !$session->has(self::FLASHBAG_SESSION_KEY)) {
            $session->set(self::FLASHBAG_SESSION_KEY, array());
        }
    }

    public function get($key, $default = null, $remove = false)
    {
        $flashes = $this->all();
        $flashMessage = array_key_exists($key, $flashes) ? $flashes[$key] : $default;

        if ($remove) {
            $this->remove($key);
        }

        return $flashMessage;
    }

    public function all($remove = false)
    {
        $flashes = $this->session->get(self::FLASHBAG_SESSION_KEY, array());
        if ($remove) {
            $this->session->remove(self::FLASHBAG_SESSION_KEY);
        }
        return $flashes;
    }

    public function remove($key)
    {
        $flashes = $this->all();
        unset($flashes[$key]);
        $this->session->set(self::FLASHBAG_SESSION_KEY, $flashes);
    }

    public function has($key)
    {
        $flashes = $this->all();
        return array_key_exists($key, $flashes);
    }

    public function set($key, $value)
    {
        $flashes = $this->all();
        $this->session->set(self::FLASHBAG_SESSION_KEY, array_merge($flashes, array($key => $value)));
    }
}