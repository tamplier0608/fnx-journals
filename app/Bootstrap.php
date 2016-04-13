<?php

use Core\Application\Bootstrap\BootstrapAbstract;
use Core\View;

/**
 * Class Bootstrap
 *
 * @package app
 * @property $appConfig
 */
class Bootstrap extends BootstrapAbstract
{
    public function __initRouter() {
        $appConfig = $this->application->getResource('appConfig');
        $router = new Core\Router(new Core\Config($appConfig['application_path'] . '/configs/routes.' . ENV . '.php'));
        $this->application->addResource('router', $router);
    }

    public function __initDb()
    {
        $dbAdapter = new \Core\Db($this->application->getResource('appConfig')['db']);

        Core\Db\Row::setDefaultDbAdapter($dbAdapter);
        Core\Db\Repository::setDefaultDbAdapter($dbAdapter);

        $this->application->addResource('db', $dbAdapter);
    }

    public function __initSession()
    {
        Core\Session::start();
    }

    public function __initLayout()
    {
        $appConfig = $this->application->getResource('appConfig');
        $request = \Core\Request::createFromGlobals();

        if (
            !empty($appConfig['layout']) &&
            !empty($appConfig['layout']['path']) &&
            !empty($appConfig['layout']['template'])
        ) {
            $layout = new View($appConfig['layout']['path']);

            # init resources
            $baseUrl = $request->getBaseUrl();
            $layout->head()->setTitle('FNX Journals');
            $layout->head()->addStyleSheet($baseUrl . '/assets/css/bootstrap.min.css');
            $layout->head()->addStyleSheet($baseUrl . '/assets/css/bootstrap-theme.min.css');
            $layout->head()->addStyleSheet($baseUrl . '/assets/css/jumbotron-narrow.css');
            $layout->head()->addScript($baseUrl . '/assets/js/jquery.js');
            $layout->head()->addScript($baseUrl . '/assets/js/bootstrap.min.js');

            # init layout variables
            $isLoggedUser = false;
            if ($request->session->has('user')) {
                $layout->assign('user', $request->session->get('user'));
                $isLoggedUser = true;
            }
            $layout->assign('isLoggedUser', $isLoggedUser);

            # set data for list of categories in sidebar
            $categories = $this->getCategoryList();
            $layout->assign('categories', $categories);

            # set data for list of authors in sidebar
            $authors = $this->getAuthorList();
            $layout->assign('authors', $authors);

            # set flashes
            $layout->assign('flashBag', $request->session->getFlashBag());

            $this->application->addResource('layout', $layout);
        }
    }

    /**
     * @return array
     */
    public function getCategoryList()
    {
        $categoryRepository = new \Entity\Repository\Categories();
        $categories = $categoryRepository->getList();
        return $categories;
    }

    public function getAuthorList()
    {
        $authorRepository = new \Entity\Repository\Authors();
        $authors = $authorRepository->fetchAll();
        return $authors;
    }

    public function __initView()
    {
        $appConfig = $this->application->getResource('appConfig');
        $view = new View($appConfig['view']['path']);
        $this->application->addResource('view', $view);
    }
}
