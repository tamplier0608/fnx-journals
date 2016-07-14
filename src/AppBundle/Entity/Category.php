<?php

namespace AppBundle\Entity;

use Core\Db\Entity;

/**
 * Class Articles
 * @package Entity
 */
class Category extends Entity
{
    protected static $table = 'categories';
    protected static $primaryKey = 'categoryId';
}