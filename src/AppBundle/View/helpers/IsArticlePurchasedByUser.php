<?php

namespace AppBundle\View\helpers;
use AppBundle\Entity\Repository\Users;

/**
 * Class Url
 * @package View\helpers
 */
class IsArticlePurchasedByUser
{
    public function isArticlePurchasedByUser($params)
    {
        list($articleId, $userId) = $params;

        if (null === $articleId || null === $userId) {
            throw new \RuntimeException('Not enough arguments for view helper "' . __CLASS__ . '"');
        }

        $userRepository = new Users();
        return $userRepository->isInCollection($articleId, $userId);
    }
}