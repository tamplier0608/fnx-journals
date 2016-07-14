<?php
/**
 * Created by PhpStorm.
 * User: serhii
 * Date: 4/4/16
 * Time: 6:21 PM
 */

namespace Core\View\helpers;


class Escape
{
    public function escape($params)
    {
        return filter_var($params[0], FILTER_SANITIZE_STRING);
    }
}