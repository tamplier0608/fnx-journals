<?php

namespace AppBundle\Entity\Repository;

use Core\Db\Repository;

/**
 * Class Comments
 * @package Entity
 */
class Comments extends Repository
{
    protected static $table = 'comments';
    protected static $primaryKey = 'commentId';
    protected static $rowClass = 'AppBundle\Entity\Comment';
    
    
    
}