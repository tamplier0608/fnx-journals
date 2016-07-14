<?php

namespace Core\Auth;

use Core\Auth\Adapter\AdapterInterface;

/**
 * Class Auth
 * @package Core\Auth
 */
class Auth
{
    protected $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function authorize($username, $password)
    {
        return $this->adapter->authenticate($username, $password);
    }
}