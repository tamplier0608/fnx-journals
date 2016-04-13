<?php

namespace Core\Db;

use Core\Db;

/**
 * Class Row
 * @package Core\Db
 */
abstract class Row
{
    /**
     * @var Table name
     */
    protected static $table;

    /**
     * @var Database adapter object
     */
    protected static $db;

    /**
     * @var string Primary keys name
     */
    protected static $primaryKey = 'id';

    public function __construct($id = null)
    {
        if (null !== $id) {
            $this->{static::$primaryKey} = $id;
            $rowObject = $this->fetch();

            if ($rowObject) {
                $this->fill($rowObject);
            } else {
                throw new \InvalidArgumentException("Entity with id = $id is not found.");
            }
        }
    }

    /**
     * @return mixed
     */
    protected function fetch()
    {
        $query = $this->buildFetchQuery();
        $sth = self::getDbAdapter()->exec($query, array($this->{static::$primaryKey}));

        if (!$sth instanceof \PDOStatement) {
            return false;
        }

        return $sth->fetchObject();
    }

    protected function buildFetchQuery()
    {
        $query = 'SELECT * FROM ' . static::$table;
        $query .= ' WHERE ' . static::$primaryKey . ' = ?';

        return $query;
    }

    public static function getDbAdapter() {
        if (null === static::$db) {
            throw new \RuntimeException('Database adapter is not set!');
        }
        return static::$db;
    }

    /**
     * @param $data
     */
    public function fill($data)
    {
        if (empty($data)) {
            return;
        }

        foreach ($data as $field => $value) {
            $this->{$field} = $value;
        }
    }

    public static function setDefaultDbAdapter(Db $db)
    {
        static::$db = $db;
    }

    /**
     * @param $field
     * @return null
     */
    public function __get($field)
    {
        if (isset($this->{$field})) {
            return $this->{$field};
        }

        return null;
    }

    /**
     * @param $field
     * @param $value
     * @return $this
     */
    public function __set($field, $value)
    {
        $this->{$field} = $value;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function isEmpty()
    {
        $reflector = new \ReflectionObject($this);
        $vars = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);

        return empty($vars);
    }

    /**
     * @return mixed
     */
    public function delete()
    {
        $query = $this->buildDeleteQuery();
        $sth = static::getDbAdapter()->exec($query, array($this->{static::$primaryKey}));

        if (!$sth instanceof \PDOStatement) {
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    protected function buildDeleteQuery()
    {
        $sql = 'DELETE FROM ' . static::$table;
        $sql .= ' WHERE ' . static::$primaryKey . ' = ?';
        return $sql;
    }

    /**
     * Save row
     *
     * @return bool Result of operation
     */
    public function save()
    {
        $sql = $this->buildSaveQuery();

        $result = static::getDbAdapter()->exec($sql);

        if (!$result instanceof \PDOStatement) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    protected function buildSaveQuery()
    {
        $query = 'INSERT INTO ' . static::$table;

        $values = array();
        $fields = array();
        $params = array();

        foreach ($this->getPublicVars() as $field => $value) {
            $fields[] = $field;
            $params[] = $field . '= "' . $value . '"';
            $values[] = '"' . $value . '"';
        }

        $query .= ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $values) . ') ';
        $query .= 'ON DUPLICATE KEY UPDATE ' . implode(',', $params);

        return $query;
    }

    protected function getPublicVars()
    {
        $me = $this;

        $vars = function () use ($me) {
            return get_object_vars($me);
        };

        return $vars();
    }

    /**
     * Get last MySQL error
     *
     * @return Error
     */
    public function getLastError()
    {
        return static::getDbAdapter()->getError();
    }

    /**
     * @param $fields
     * @return $this
     */
    public function setFields($fields)
    {
        $this->fill($fields);

        return $this;
    }
}
