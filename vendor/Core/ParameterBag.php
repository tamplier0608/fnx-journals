<?php

namespace Core;

use Core\Bag\ParameterBagInterface;

/**
 * Class Bag
 * @package Core\Bag
 */
class ParameterBag implements ParameterBagInterface
{
    protected $parameters = array();

    public function __construct(array $data = array())
    {
        $this->parameters = $data;
    }

    public function get($key, $default = null)
    {
        return !empty($this->parameters[$key]) ? $this->parameters[$key] : $default;
    }

    public function set($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    public function remove($key)
    {
        if (!empty($this->parameters[$key])) {
            unset($this->parameters[$key]);
        }
    }

    public function has($key)
    {
        return array_key_exists($key, $this->parameters);
    }
    
    public function all()
    {
        return $this->parameters;
    }
}