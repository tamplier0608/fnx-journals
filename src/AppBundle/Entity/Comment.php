<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Repository\Users;
use Core\Db\Entity;

/**
 * Class Articles
 * @package Entity
 */
class Comment extends Entity
{
    protected static $table = 'comments';
    protected static $primaryKey = 'commentId';
    protected static $avoidSaving = array('user');

    public $user;

    public function getUser()
    {
        if (null === $this->user) {
            $userRepository = new Users();
            $this->user = $userRepository->find($this->userId);
        }
        return $this->user;
    }
}