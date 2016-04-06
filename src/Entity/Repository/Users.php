<?php

namespace Entity\Repository;

use Core\Db\Repository;

/**
 * Class Articles
 * @package Entity\Repository
 */
class Users extends Repository
{
    protected static $table = 'users';
    protected static $rowClass = 'Entity\User';

    public function fetchOneByUsername($articleId)
    {
        $sql = $this->buildFetchAllByArticleQuery();
        $sth = $this->executeQuery($sql, array($articleId));
        $row = $sth->fetchObject(self::$rowClass);

        return $row;
    }
    
    protected function buildFetchAllByArticleQuery()
    {
        $sql = <<<SQL
            SELECT * FROM users WHERE username = ?;
SQL;

        return $sql;
    }

    public function isInCollection($articleId, $userId)
    {
        $sql = <<<SQL
            SELECT (CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END) as isInUserCollection FROM articles art
            JOIN orders ord ON art.articleId = ord.articleId
            WHERE art.articleId = ?
            AND ord.customerId = ?
SQL;
        $sth = self::getDbAdapter()->exec($sql, array($articleId, $userId));

        if (!$sth instanceof \PDOStatement) {
            return false;
        }
        $result = $sth->fetch(\PDO::FETCH_ASSOC);

        return $result['isInUserCollection'];
    }
}