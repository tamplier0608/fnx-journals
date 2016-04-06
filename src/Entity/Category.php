<?php

namespace Entity;

use Core\Db\Row;

/**
 * Class Articles
 * @package Entity
 */
class Category extends Row
{
    protected static $table = 'categories';
    protected static $primaryKey = 'categoryId';
}