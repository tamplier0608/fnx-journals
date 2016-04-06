<?php

namespace Entity;

use Core\Db\Row;

/**
 * Class Articles
 * @package Entity
 */
class Article extends Row
{
    protected static $table = 'articles';
    protected static $primaryKey = 'articleId';
}