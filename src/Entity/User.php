<?php

namespace Entity;

use Core\Db\Row;

/**
 * Class Articles
 * @package Entity
 */
class User extends Row
{
    protected static $table = 'users';
    protected static $primaryKey = 'userId';
}