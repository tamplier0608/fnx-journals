<?php

namespace Core\Auth\Adapter;

/**
 * Interface AdapterInterface
 * @package Core\Auth\Adapter
 */
interface AdapterInterface
{
    public function authenticate($username, $password);
}