<?php

namespace Entity;

use Core\Db\Row;

/**
 * Class Articles
 * @package Entity
 */
class Comment extends Row
{
    protected static $table = 'comments';
    protected static $primaryKey = 'commentId';
}