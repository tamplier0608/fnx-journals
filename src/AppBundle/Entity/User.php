<?php

namespace AppBundle\Entity;

use Core\Db\Entity;

/**
 * Class Articles
 * @package Entity
 */
class User extends Entity
{
    protected static $table = 'users';
    protected static $primaryKey = 'userId';
}