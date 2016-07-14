<?php

class ResponseTest extends PHPUnit_Framework_TestCase
{
    protected $response;

    public function setUp()
    {
        $this->response = new \Core\Response();
    }

    public function testSendContent()
    {
        $content = <<<HTML
<html>
    <head><title>Title</title></head>
    <body>Body</body>
</html>
HTML;

        $this->response->setContent($content);
        ob_start();
        $this->response->sendContent();
        $result = ob_get_clean();

        $this->assertEquals($content, $result);
    }

}
