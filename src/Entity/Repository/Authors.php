<?php

namespace Entity\Repository;

use Core\Db\Repository;

/**
 * Class Articles
 * @package Entity\Repository
 */
class Authors extends Repository
{
    protected static $table = 'authors';
    protected static $rowClass = 'Entity\Author';

    public function fetchAllByArticle($articleId)
    {
        $sql = $this->buildFetchAllByArticleQuery();
        $sth = $this->executeQuery($sql, array($articleId));
        $rowSet = $this->fetchResultInRowset($sth);

        return $rowSet;
    }
    
    protected function buildFetchAllByArticleQuery()
    {
        $sql = <<<SQL
            SELECT * FROM `authors`
            JOIN `articles_authors` a_u ON `authors`.`authorId` = a_u.`authorId`
            WHERE a_u.`articleId` = ?;
SQL;
        
        return $sql;
    }
}