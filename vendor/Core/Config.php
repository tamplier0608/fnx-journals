<?php

namespace Core;

class Config implements \ArrayAccess, \Iterator
{
    protected $data;

    public function __construct($config)
    {
        if (file_exists($config)) {
            $this->data = require $config;
        } else {
            throw new \RuntimeException('Config file "' . $config . '" does not exist!');
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return !empty($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return !empty($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        return next($this->data);
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return array_key_exists(key($this->data), $this->data);
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        reset($this->data);
    }
}
