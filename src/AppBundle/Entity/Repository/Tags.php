<?php

namespace AppBundle\Entity\Repository;

use Core\Db\Repository;

/**
 * Class Articles
 * @package Entity\Repository
 */
class Tags extends Repository
{
    protected static $table = 'tags';
    protected static $primaryKey = 'tagId';
    protected static $rowClass = 'AppBundle\Entity\Tag';

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

    public function getList()
    {
        $query = <<<SQL
            SELECT t.*, COUNT(articleId) as c_art FROM tags t
            JOIN articles_tags a_t ON t.tagId = a_t.tagId
            GROUP BY a_t.tagId
            ORDER BY c_art DESC
SQL;
        $sth = $this->executeQuery($query);
        return $this->fetchResultInRowset($sth);
    }
}