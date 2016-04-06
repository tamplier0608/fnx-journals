<?php

namespace Entity;

use Core\Db\Row;

/**
 * Class Articles
 * @package Entity
 */
class Author extends Row
{
    protected static $table = 'authors';
    protected static $primaryKey = 'authorId';
}