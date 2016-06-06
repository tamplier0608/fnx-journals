<?php

namespace AppBundle\Entity\Repository;

use Core\Db\Repository;

/**
 * Class Categories
 * @package Entity\Repository
 */
class Categories extends Repository
{
    protected static $table = 'categories';
    protected static $primaryKey = 'categoryId';
    protected static $rowClass = 'AppBundle\Entity\Category';

    public function getList()
    {
        $sql = <<<SQL
            SELECT cat.*, COUNT(art.articleId) as art_count FROM categories cat
            LEFT JOIN articles art ON cat.categoryId = art.categoryId
            GROUP BY cat.categoryId
            ORDER BY art_count DESC;
SQL;
        $sth = $this->executeQuery($sql);
        $rowSet = $this->fetchResultInRowset($sth);

        return $rowSet;
    }
}