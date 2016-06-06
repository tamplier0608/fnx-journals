<?php

namespace AppBundle\View\helpers;

use Core\Request;

/**
 * Class GetUser
 * @package View\helpers
 */
class GetUser
{
    public function getUser()
    {
        $request = Request::createFromGlobals();
        return $request->session->get('user');
    }
}