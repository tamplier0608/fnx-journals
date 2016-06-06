<?php

namespace AppBundle\Entity\Repository;

use Core\Db\Repository;

/**
 * Class Articles
 * @package Entity\Repository
 */
class Orders extends Repository
{
    protected static $table = 'orders';
    protected static $primaryKey = 'orderId';
    protected static $rowClass = 'AppBundle\Entity\Order';

    public function fetchAllByUser($userId)
    {
        $sql = $this->buildFetchAllByUserQuery();
        $sth = $this->executeQuery($sql, array($userId));
        $rowSet = $this->fetchResultInRowset($sth);

        return $rowSet;
    }
    
    protected function buildFetchAllByUserQuery()
    {
        $sql = <<<SQL
            SELECT art.articleId, art.title, art.shortDescription, 
              ord.orderId, ord.price, ord.orderDate, ord.customerId
            FROM articles art
            JOIN orders ord ON art.articleId = ord.articleId
            JOIN users u ON ord.customerId = u.userId
            WHERE u.userId = ?;
SQL;

        return $sql;
    }
}