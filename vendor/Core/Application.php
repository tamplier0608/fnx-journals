<?php

namespace Core;

use Core\Application\Exception as ApplicationException;
use Core\Response\ResponseInterface;

/**
 * Class Application
 * @package Core
 */
class Application implements \ArrayAccess
{
    /**
     * @var
     */
    protected $config;

    /**
     * @var Application environment
     */
    protected $env;

    protected $registry;

    public function __construct(Config $config, $env = 'prod')
    {
        $this->config = !empty($config) ? $config : array();
        $this->env = $env;

        $this->addResource('appConfig', $this->config);
    }

    /**
     * Get resource by identifier
     *
     * @param string $identifier
     * @return mixed
     */
    public function getResource($identifier)
    {
        return $this->offsetGet($identifier);
    }

    /**
     * Adds resource to app registry
     *
     * @param string $identifier Name of resource which it will be resigered in registry
     * @param mixed $resource Resource value
     * @return Application
     */
    public function addResource($identifier, $resource)
    {
        $this->offsetSet($identifier, $resource);
        return $this;
    }

    /**
     * @param string $identifier
     * @return bool
     */
    public function hasResourse($identifier)
    {
        return $this->offsetExists($identifier);
    }

    /**
     * Start app
     * @throws ApplicationException
     */
    public function handle(Request $request)
    {
        $requestUri = $this->getUriFromRequest($request);
        $routeParams = $this->offsetGet('router')->match($requestUri);

        try {
            if (false === $routeParams) {
                throw new ApplicationException('Route is not found.');
            }

            extract($routeParams, EXTR_OVERWRITE);
            if (null === $controller || null === $action || null === $params) {
                throw new ApplicationException('Wrong route parameters for uri "' . $requestUri . '". Check your config.');
            }

            $controllerClass = $this->buildControllerClassName($controller);
            $controllerFile = $this->buildControllerFileName($controllerClass);

            if (false === file_exists($this->config['source_path'] . $controllerFile)) {
                throw new ApplicationException('File of controller "' . $controllerFile . '" does not exist."' );
            }

            $controllerObj = new $controllerClass($this, $request);
            $controllerObj->init();

            $actionMethod = $this->buildActionMethodName($action);
            if (false === method_exists($controllerObj, $actionMethod)) {
                throw new ApplicationException('Action does not exist: ' . $actionMethod);
            }
            
            $response = call_user_func_array(array($controllerObj, $actionMethod), $params);
            $controllerObj->postDispatch();


            if (!$response instanceof ResponseInterface) {
                throw new ApplicationException('Controller must return instance of Response class!');
            }

            return $response;
        } catch (HttpException $e) {
            if (ENV === 'prod') {
                throw new HttpException($e->getMessage(), 404);
            }
            throw $e;
        } catch (ApplicationException $e) {
            if (ENV === 'prod') {
                throw new HttpException('Page not found', 404);
            }
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @return array|null
     */
    protected function getUriFromRequest(Request $request)
    {
        $requestUri = $request->server->get('REQUEST_URI');
        $segments = explode('?', $requestUri);
        $uri = $segments[0];

        if ($uri !== '/') {
            $uri = substr($uri, strlen($request->getBaseUrl()));

            if ($uri !== '/' && strpos($uri, '/') === 0) {
                $uri = substr($uri, 1);
                return $uri;
            }
        }

        return $uri;
    }


    /**
     * @param $controller
     * @return string
     */
    public function buildControllerClassName($controller)
    {
        $controllerClass = 'Controller\\' . ucfirst($controller) . 'Controller';
        return $controllerClass;
    }

    /**
     * @param $controllerClass
     * @return mixed
     */
    public function buildControllerFileName($controllerClass)
    {
        return str_replace("\\", "/", $controllerClass . '.php');
    }

    /**
     * @param $action
     * @return string
     */
    public function buildActionMethodName($action)
    {
        $actionMethod = $action . 'Action';
        return $actionMethod;
    }

    /**
     * @return Application
     */
    public function bootstrap()
    {
        if (file_exists($this->config['bootstrap']['main_path'] . $this->config['bootstrap']['file'])) {

            require_once $this->config['bootstrap']['main_path'] . $this->config['bootstrap']['file'];

            $appBoot = new \Bootstrap($this);
            $appBoot->bootstrap();
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        if (isset($this->registry[$offset])) {
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        if (isset($this->registry[$offset])) {
            return $this->registry[$offset];
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        $this->registry[$offset] = $value;
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        unset($this->registry[$offset]);
    }
}
