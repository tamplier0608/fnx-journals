<?php

namespace Core\Db;

use Core\Db;

/**
 * Class Row
 * @package Core\Db
 */
class Row
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

    protected function getPublicVars()
    {
        $me = $this;

        $vars = function () use ($me) {
            return get_object_vars($me);
        };

        return $vars();
    }

    /**
     * @return mixed
     */
    protected function fetch()
    {
        $db = self::getDbAdapter();

        $sql = 'SELECT * FROM `' . static::$table . '`';
        $sql .= ' WHERE `' . static::$primaryKey . '` = ?';
        $sth = $db->exec($sql, array($this->{static::$primaryKey}));

        if (!$sth instanceof \PDOStatement) {
            return false;
        }

        return $sth->fetchObject();
    }

    /**
     * @return mixed
     */
    public function delete()
    {
        $db = static::getDbAdapter();

        $sql = 'DELETE FROM `' . static::$table . '`';
        $sql .= ' WHERE `' . static::$primaryKey . '` = ?';

        $sth = $db->exec($sql, array($this->{static::$primaryKey}));

        if (!$sth instanceof \PDOStatement) {
            return false;
        }
        return true;
    }

    /**
     * Save row
     *
     * @return bool Result of operation
     */
    public function save()
    {
        $sql = 'INSERT INTO ' . static::$table;

        $values = '';
        $fields = ' (';
        $params = '';

        foreach ($this->getPublicVars() as $field => $value) {
            $fields .= '`' . $field . '`, ';
            $params .= '`' . $field . '` = "' . $value . '", ';

            $values .= '"' . $value . '", ';
        }

        $fields = rtrim($fields, ', ') . ')';
        $values = rtrim($values, ', ');
        $params = rtrim($params, ', ');

        $sql .= $fields . ' VALUES (' . $values . ') ';
        $sql .= 'ON DUPLICATE KEY UPDATE ' . $params;

        $db = static::getDbAdapter();

        $result = $db->exec($sql);
        if (!$result instanceof \PDOStatement) {
            return false;
        }

        return true;
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
