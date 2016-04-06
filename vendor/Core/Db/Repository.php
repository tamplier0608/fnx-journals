<?php

namespace Core\Db;
use Core\Db;

/**
 * Class Repository
 * @package Core\Db
 */
class Repository
{
    protected static $table;
    protected static $rowClass;

    protected static $db;

    public function countAll()
    {
        $sth = $this->executeQuery('SELECT COUNT(*) as count FROM ' . static::$table);
        return $sth->fetch(\PDO::FETCH_ASSOC)['count'];
    }

    /**
     * @param bool|string $limit
     * @param string|array $fields
     * @return mixed
     */
    public function fetchAll($limit = false, $fields = '*')
    {
        if (is_array($fields) && count($fields)) {
            $fields = implode(',', $fields);
        }
        $sql = $this->buildFetchAllQuery($fields, $limit);
        $sth = $this->executeQuery($sql);
        $rowSet = $this->fetchResultInRowset($sth);

        return $rowSet;
    }

    /**
     * @param $fields
     * @param $limit
     * @return mixed
     */
    protected function buildFetchAllQuery($fields, $limit)
    {
        $sql = 'SELECT ' . $fields . ' FROM `' . static::$table . '`';
        if (false !== $limit) {
            $sql .= ' LIMIT ' . $limit;
        }

        return $sql;
    }

    /**
     * @param $sql
     * @return mixed
     */
    protected function executeQuery($sql, $params = array())
    {
        $sth = static::$db->exec($sql, $params);

        if (!$sth instanceof \PDOStatement) {
            throw new \RuntimeException("Error in SQL statement: '$sql'. " . __CLASS__ );
        }
        return $sth;
    }

    /**
     * @param $sth
     * @return array
     */
    protected function fetchResultInRowset($sth)
    {
        $rowSet = array();

        while ($row = $sth->fetchObject(static::$rowClass)) {
            $rowSet[] = $row;
        }
        return $rowSet;
    }

    public static function beginTransaction()
    {
        static::$db->exec('BEGIN TRANSACTION;');
    }

    public static function commitTransaction()
    {
        static::$db->exec('COMMIT');
    }

    public static function rollbackTransaction()
    {
        static::$db->exec('ROLLBACK;');
    }

    public static function setDefaultDbAdapter(Db $db)
    {
        static::$db = $db;
    }

    public static function getDbAdapter() {
        if (null === static::$db) {
            throw new \RuntimeException('Database adapter is not set!');
        }
        return static::$db;
    }
}