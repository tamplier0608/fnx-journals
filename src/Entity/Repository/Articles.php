<?php

namespace Entity\Repository;

use Core\Db\Repository;

/**
 * Class Articles
 * @package Entity\Repository
 */
class Articles extends Repository
{
    protected static $table = 'articles';
    protected static $rowClass = 'Entity\Article';

    /**
     * @inheritdoc
     */
    public function buildFetchAllQuery($fields, $limit = false)
    {
        $sql = <<<SQL
            SELECT {$fields} FROM `articles` art
            LEFT JOIN `categories` cat ON art.categoryId = cat.categoryId
SQL;

        if (false !== $limit) {
            $sql .= ' LIMIT ' . $limit;
        }

        return $sql;
    }

    public function fetchAllByAuthorId($authorId, $limit = false)
    {
        $sql = $this->buildFetchAllByAuthorQuery($limit);
        $sth = $this->executeQuery($sql, array($authorId));
        $rowSet = $this->fetchResultInRowset($sth);

        return $rowSet;
    }
    
    protected function buildFetchAllByAuthorQuery($limit = false)
    {
        $sql = <<<SQL
            SELECT * FROM articles art 
            JOIN articles_authors art_aut ON art.articleId = art_aut.articleId 
            WHERE art_aut.authorId = ?;
SQL;

        if (false !== $limit) {
            $sql .= ' LIMIT ' . $limit;
        }
        return $sql;
    }

    public function fetchAllByCategory($categoryId, $limit = false)
    {
        $sql = $this->buildFetchAllByCategoryQuery($limit);
        $sth = $this->executeQuery($sql, array($categoryId));
        $rowSet = $this->fetchResultInRowset($sth);

        return $rowSet;
    }

    /**
     * @param bool $limit
     * @param string|array $fields
     * @return string
     */
    protected function buildFetchAllByCategoryQuery($limit = false, $fields = '*')
    {
        if (is_array($fields) && count($fields)) {
            $fields = implode(',', $fields);
        }

        $sql = <<<SQL
          SELECT {$fields} FROM articles art 
          JOIN categories cat ON art.categoryId = cat.categoryId 
          WHERE cat.categoryId = ?
SQL;
        if (false !== $limit) {
            $sql .= ' LIMIT ' . $limit;
        }

        return $sql;
    }

    public function countAllInCategory($categoryId)
    {
        $sql = $this->buildFetchAllByCategoryQuery(false, 'COUNT(*) as count');
        $sth = $this->executeQuery($sql, array($categoryId));

        return $sth->fetch(\PDO::FETCH_ASSOC)['count'];
    }
}