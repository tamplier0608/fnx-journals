<?php

namespace Core\Application;

use Core\Application;
use Core\RedirectResponse;
use Core\Request;
use Core\Response;

/**
 * Class Controller
 * @package Core\Application
 */
class Controller
{
    /**
     * @var
     */
    public $view;

    public $appConfig;

    protected $application;

    protected $request;
    
    public function __construct(Application $application, Request $request)
    {
        $this->application = $application;
        $this->appConfig = $this->application->getResource('appConfig');
        $this->request = $request;
        $this->view = $this->application->getResource('view');
    }

    public function render($template, array $params = array())
    {
        if (false === $this->application->hasResourse('view')) {
            throw new \RuntimeException('View object is not initialized in bootstrap.');
        } else {
            $view = $this->application->getResource('view');
        }
        $content = $view->fetch($template, $params);

        if ($this->application->hasResourse('layout')) {
            $layout = $this->application->getResource('layout');
            $layoutContent = $layout->fetch($this->appConfig['layout']['template'], array(
                'content' => $content
            ));
            return new Response($layoutContent);
        }
        return new Response($content);
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @return null
     */
    public function getAppConfig()
    {
        return $this->appConfig;
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function init()
    {
        
    }

    public function postDispatch()
    {
        
    }

    protected function redirect($url)
    {
        return new RedirectResponse($url);
    }

}
