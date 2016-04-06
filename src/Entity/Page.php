<?php

namespace Entity;

class Page extends \Core\Db\Row
{
    protected static $table = 'pages';
    
    public static function getRecordByAlias( $alias ) 
    {
        $sql = 'SELECT * FROM `' . self::$table . '`';
        $sql .= ' WHERE `alias` = ?';
        $sth = self::$db->exec($sql, array($alias));

       if ( !$sth instanceof \PDOStatement ) {
            return false;
        }

        $result = $sth->fetchObject();

        $row = new self();
        
        if ( false !== $result ) {
            foreach ( $result as $name => $value ) {
                $row->$name = $value;
            }
        }

        return $row;
    }
    
    public static function getAll(array $filters = array('status' => 'open'))
    {

        $sql = 'SELECT * FROM `' . self::$table . '`';
        
        if (isset($filters['order'])) {
            $order = $filters['order'];
            unset($filters['order']);
        }
        
        if (count($filters)) {
            $sql .= ' WHERE';
            
            foreach ($filters as $key => $value) {
                if ($key == 'id') {
                    $sql .= '`' . self::$table . '`.`' . self::$primaryKey . '` = ' . $value;
                } else {
                    $sql .= " `$key` LIKE '%$value%' AND";
                }
            }
        }        
        $sql = rtrim($sql, ' AND');
        
        if (!empty($order)) {
            $sql .= ' ORDER BY ' . $order;
        } else {
            $sql .= ' ORDER BY ' . self::$primaryKey . ' DESC';
        }

        $db = \Core\Registry::get('dbAdapter');
        $sth = $db->exec($sql);  

        if ( !$sth instanceof \PDOStatement ) {
            return false;
        }

        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }
}