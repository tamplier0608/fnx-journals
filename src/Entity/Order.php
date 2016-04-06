<?php

namespace Entity;

use Core\Db\Row;

/**
 * Class Articles
 * @package Entity
 */
class Order extends Row
{
    protected static $table = 'orders';
    protected static $primaryKey = 'orderId';
}