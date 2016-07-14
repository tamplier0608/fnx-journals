<?php

namespace AppBundle\Entity;

use Core\Db\Entity;

/**
 * Class Articles
 * @package Entity
 */
class Author extends Entity
{
    protected static $table = 'authors';
    protected static $primaryKey = 'authorId';
}