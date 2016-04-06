<?php

namespace Core\Auth\Adapter;

/**
 * Class Db
 * @package Core\Auth\Adapter
 */
class DbTable implements AdapterInterface
{
    protected $db;
    protected $tableName;
    protected $identityColumn;
    protected $credentialColumn;

    public function __construct($db, $tableName, $identityColumn, $credentialColumn)
    {
        $this->db = $db;
        $this->tableName = $tableName;
        $this->identityColumn = $identityColumn;
        $this->credentialColumn;
    }

    public function authenticate($username, $password)
    {
        $password = md5($password . $this->db->getConfig()['crypt_salt']);
        $sql = <<<SQL
            SELECT (CASE WHEN `password` = ? THEN 1 ELSE 0 END) credential_match 
            FROM `users`
            WHERE `username` = ?;
SQL;
        $sth = $this->db->exec($sql, array($password, $username));

        if (!$sth instanceof \PDOStatement) {
            return false;
        }

        return $this->validateResult($sth->fetch(\PDO::FETCH_ASSOC));
    }

    protected function validateResult($result)
    {
        return ($result['credential_match'] > 0);
    }
}