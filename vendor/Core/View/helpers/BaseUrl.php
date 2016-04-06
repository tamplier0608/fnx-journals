<?php

namespace Core\View\helpers;

use Core\Request;

class BaseUrl
{
    public function baseUrl()
    {
        return Request::createFromGlobals()->getBaseUrl();
    }
}