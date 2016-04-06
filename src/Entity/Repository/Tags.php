<?php

namespace Entity\Repository;

use Core\Db\Repository;

/**
 * Class Articles
 * @package Entity\Repository
 */
class Tags extends Repository
{
    protected static $table = 'tags';
    protected static $rowClass = 'Entity\Tag';

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
            SELECT * FROM `tags` 
            JOIN `articles_tags` a_t ON tags.tagId = a_t.tagId 
            WHERE a_t.articleId = ?;
SQL;

        return $sql;
    }
}