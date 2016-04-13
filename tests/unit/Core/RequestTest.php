<?php

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Core\Request::getBaseUrl()
     * @dataProvider uriProvider
     */
    public function testGetBaseUrl($requestUri, $scriptName, $expected)
    {
        $_SERVER['REQUEST_URI'] = $requestUri;
        $_SERVER['SCRIPT_NAME'] = $scriptName;
        $_SERVER['PHP_SELF'] = $scriptName;

        $request = \Core\Request::createFromGlobals();
        $baseUrl = $request->getBaseUrl();

        $this->assertEquals($expected, $baseUrl);
    }

    public function uriProvider()
    {
        return array(
            array('//', 'index.php', '/'),
            array('/', '/index.php', '/'),
            array('/fnx-journals/', '/fnx-journals/index.php', '/fnx-journals'),
            array('/fnx-journals/user/1', '/fnx-journals/index.php', '/fnx-journals'),
            array('/common/fnx-journals/user/1', '/common/fnx-journals/index.php', '/common/fnx-journals'),
        );
    }

}
