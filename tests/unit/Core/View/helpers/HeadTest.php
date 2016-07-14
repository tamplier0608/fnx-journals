<?php

class HeadTest extends PHPUnit_Framework_TestCase
{
    public function testAddStyleSheet()
    {
        $head = new \Core\View\helpers\Head();
        $head->addStylesheet('ui/js/style.css');
        $head->addStylesheet('ui/js/style2.css');

        echo $head->getStylesheets();
    }
}
