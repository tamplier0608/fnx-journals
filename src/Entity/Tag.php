<?php

namespace Entity;

use Core\Db\Row;

/**
 * Class Articles
 * @package Entity
 */
class Tag extends Row
{
    protected static $table = 'tags';
    protected static $primaryKey = 'tagId';
}