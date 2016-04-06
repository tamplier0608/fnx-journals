<?php

namespace Core;

/**
 * Class ServerBag
 * @package Core
 */
class ServerBag extends ParameterBag
{
    public function getHeaders()
    {
        return array_change_key_case(getallheaders(), CASE_LOWER);
    }
}