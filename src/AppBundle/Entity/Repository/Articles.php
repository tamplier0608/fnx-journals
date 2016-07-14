<?php

namespace AppBundle\Entity\Repository;

use Core\Db\Repository;

/**
 * Class Articles
 * @package Entity\Repository
 */
class Articles extends Repository
{
    protected static $table = 'articles';
    protected static $primaryKey = 'articleId';
    protected static $rowClass = 'AppBundle\Entity\Article';

    /**
     * @inheritdoc
     */
    public function buildFetchAllQuery($limit = false, $orderBy = false)
    {
        $sql = <<<SQL
            SELECT * FROM `articles` art
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

    public function fetchAllByCategory($categoryId, $limit = false, $orderBy = false)
    {
        $sql = $this->buildFetchAllByCategoryQuery($limit, $orderBy);
        $sth = $this->executeQuery($sql, array($categoryId));
        $rowSet = $this->fetchResultInRowset($sth);

        return $rowSet;
    }

    /**
     * @param bool $limit
     * @param string|array $fields
     * @return string
     */
    protected function buildFetchAllByCategoryQuery($limit = false, $orderBy = false)
    {
        $sql = <<<SQL
          SELECT * FROM articles art 
          JOIN categories cat ON art.categoryId = cat.categoryId 
          WHERE cat.categoryId = ?
SQL;
        if (false !== $orderBy) {
            $sql .= ' ORDER BY ' . $orderBy;
        }

        if (false !== $limit) {
            $sql .= ' LIMIT ' . $limit;
        }

        return $sql;
    }

    public function fetchAllByTag($tagId, $limit = false, $orderBy = false)
    {
        $query = $this->builFetchAllByTagQuery($limit, $orderBy);
        $sth = $this->executeQuery($query, array($tagId));
        return $this->fetchResultInRowset($sth);
    }

    protected function builFetchAllByTagQuery($limit = false, $orderBy = false)
    {
        $query = <<<SQL
          SELECT * FROM articles art
          JOIN articles_tags a_t ON art.articleId = a_t.articleId
          WHERE a_t.tagId = ?
SQL;


        if (false !== $orderBy) {
            $query .= ' ORDER BY ' . $orderBy;
        }

        if (false !== $limit) {
            $query .= ' LIMIT ' . $limit;
        }

        return $query;
    }

    public function countAllInCategory($categoryId)
    {
        $sql = <<<SQL
          SELECT COUNT(*) as count FROM articles art
          JOIN articles_tags a_t ON art.articleId = a_t.articleId
          WHERE a_t.tagId = ?
SQL;

        $sth = $this->executeQuery($sql, array($categoryId));

        return $sth->fetch(\PDO::FETCH_ASSOC)['count'];
    }
}