<?php

namespace Core;

use Core\Db\Error;
use Core\Registry;

class Db
{
    /**
     * @var \PDO PDO driver instance
     */
    protected static $pdo;

    /**
     * @var \PDOStatements PDOStatements instance
     */
    protected static $pdoStatement;

    /**
     * @var Error|null Error object
     */
    protected $error;

    protected $config;

    public function __construct($config)
    {
        if (!empty($config)) {
            $this->config = $config;

            $dsn = $config['adapter'] . ':host=' . $config['host'] .
                ';dbname=' . $config['dbname'];
            if (!empty($config['charset'])) {
                $dsn .= ';charset=' . $config['charset'];
            }

            try {
                self::$pdo = new \PDO($dsn, $config['username'], $config['password']);
            } catch (\PDOException $e) {
                die('PDO Exception: ' . $e->getMessage());
            }
        } else {
            throw new \PDOException('Database adapter is not configured!');
        }
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Execute MySQL query
     *
     * @param  string $sql Query string
     * @param  array $params Query params
     * @return \PDOStatements
     */
    public function exec($sql, array $params = array())
    {
        $sth = static::$pdo->prepare($sql);
        $sth->execute($params);

        $errorInfo = $sth->errorInfo();

        if ($errorInfo[0] != 00000) {
            $this->setError(new Error($errorInfo[1], $errorInfo[2]));
            return false;
        }

        return self::$pdoStatement = $sth;
    }

    /**
     * Set SQL error object
     *
     * @param Error $error
     * @return $this
     */
    protected function setError(Error $error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Get error object
     *
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Call PDO drivers methods
     *
     * @param  string $name Name of methods
     * @param  array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array(static::$pdo, $name), $arguments);
    }
}