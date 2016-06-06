<?php

namespace AppBundle\Entity;

use Core\Db\Entity;

/**
 * Class Articles
 * @package Entity
 */
class Order extends Entity
{
    protected static $table = 'orders';
    protected static $primaryKey = 'orderId';
}