<?php

namespace Core;

/**
 * Class Router
 * @package Core
 */
class Router
{
    protected $request;
    protected $routes;

    public function __construct(Config $routes)
    {
        $this->routes = $routes;
    }

    public function match($uri)
    {
        foreach ($this->routes as $name => $route) {
            $pattern = array_key_exists('pattern', $route) ? $route['pattern'] : '';
            
            if (preg_match("~$pattern~", $uri, $matches)) {
                $routeUri = array_key_exists('uri', $route) ? $route['uri'] : '';

                $intUri = preg_replace("~$pattern~", $routeUri, $uri);
                $segments = explode('/', $intUri);

                return array(
                    'controller' => array_shift($segments),
                    'action' => array_shift($segments),
                    'params' => $segments
                );
            }
        }
        return false;
    }
}