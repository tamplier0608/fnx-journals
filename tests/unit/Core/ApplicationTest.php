<?php

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    protected $app;
    
    public function setUp()
    {
        $config = new Core\Config(FIXTURES_DIR . '/configs/application.dev.php');
        $this->app = new Core\Application($config, 'test');
        $router = new Core\Router(new Core\Config(FIXTURES_DIR . '/configs/routes.dev.php'));
        $this->app->addResource('router', $router);
    }

    public function tearDown()
    {
        
    }

    /**
     * @covers Core\Application:getUriFromRequest()
     * @dataProvider uriScriptNameProvider
     */
    public function testFetUriFromRequest($requestUri, $scriptName, $expected)
    {
        $request = $this->createFakeRequest($requestUri, $scriptName);
        
        $reflection = new ReflectionClass('\Core\Application');
        $method = $reflection->getMethod('getUriFromRequest');
        $method->setAccessible(true);

        $result = $method->invoke($this->app, $request);

        $this->assertEquals($expected, $result);
    }

    /**
     * @param $requestUri
     * @param $scriptName
     * @return static
     */
    public function createFakeRequest($requestUri, $scriptName)
    {
        $_SERVER['REQUEST_URI'] = $requestUri;
        $_SERVER['SCRIPT_NAME'] = $scriptName;
        $_SERVER['PHP_SELF'] = $scriptName;
        $request = \Core\Request::createFromGlobals();
        return $request;
    }

    public function uriScriptNameProvider()
    {
        return array(
            array('/', 'index.php', '/'),
            array('/fnx-journals/', '/fnx-journals/index.php', '/'),
            array('/fnx-journals/user/1', '/fnx-journals/index.php', 'user/1'),
            array('/common/fnx-journals/user/1', '/common/fnx-journals/index.php', 'user/1'),
        );
    }

    public function testHandleExceptionRouteNotFound()
    {
        $request = $this->createFakeRequest('/fnx-journals/article/1', '/fnx-journals/index.php');

        $this->setExpectedException('\Core\Application\Exception', 'Route is not found.');
        $this->app->handle($request);
    }

    public function testHandleExceptionWrongRouteParams()
    {
        $request = $this->createFakeRequest('/fnx-journals/contacts/form', '/fnx-journals/index.php');
        $this->setExpectedException('\Core\Application\Exception', 'Wrong route parameters');
        $this->app->handle($request);
    }

    public function testHandleExceptionControllerNotFound()
    {
        $request = $this->createFakeRequest('/fnx-journals/error/404/', '/fnx-journals/index.php');
        $this->setExpectedException('\Core\Application\Exception', 'File of controller');
        $this->app->handle($request);
    }

    public function testHandleActionNotExist()
    {
        $request = $this->createFakeRequest('/fnx-journals/article-list', '/fnx-journals/index.php');
        $this->setExpectedException('\Core\Application\Exception', 'Action does not exist: ');
        $this->app->handle($request);
    }
}
