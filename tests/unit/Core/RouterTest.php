<?php

class RouterTest extends \PHPUnit_Framework_TestCase
{
    protected $router;

    public function setUp()
    {
        $routes = new Core\Config(TEST_ROOT . '/fixtures/configs/routes.dev.php');
        $this->router = new \Core\Router($routes);
    }

    /**
     * @covers Core\Router::match()
     * @dataProvider uriProvider
     */
    public function testMatch($uri, $expected)
    {
        $route = $this->router->match($uri);

        $params = implode('&', $route['params']);
        $result = implode('/', array($route['controller'], $route['action'], $params));

        $this->assertEquals($expected, $result);

    }

    public function uriProvider()
    {
        return array(
            array('user/11', 'user/show/11'),
            array('page/23', 'page/show/23'),
            array('/', 'index/index/'),
            array('error/404', 'error/error/')
        );
    }
}
