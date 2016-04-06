<?php

namespace Core\Bag;

/**
 * Class BatchInterface
 * @package Core\Bag
 */
interface ParameterBagInterface
{
    public function get($key);

    public function has($key);

    public function set($key, $value);

    public function remove($key);

    public function all();
}