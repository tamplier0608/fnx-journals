<?php

namespace AppBundle\View\helpers;

use Core\Request;

/**
 * Class IsLoggedUser
 * @package View\helpers
 */
class IsLoggedUser
{
    public function isLoggedUser()
    {
        $request = Request::createFromGlobals();
        return $request->session->has('user');
    }
}