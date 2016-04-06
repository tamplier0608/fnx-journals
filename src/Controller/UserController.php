<?php

namespace Controller;

use Core\Application\Controller;
use Core\Auth\Adapter\DbTable;
use Core\Auth\Auth;
use Entity\Article;
use Entity\Order;
use Entity\Repository\Orders as OrderRepository;
use Entity\Repository\Users as UserRepository;

class UserController extends Controller
{
    public function postDispatch()
    {

    }

    public function loginAction()
    {
        $request = $this->getRequest();

        if ($this->isLoggedUser()) {
            $url = $request->session->has('ref_page') ? $request->session->get('ref_page') : $this->getView()->baseUrl();
            return $this->redirect($url);
        }

        $db = $this->application->getResource('db');
        
        if ($request->isPost() && $this->isLoginFormValid($request->post->all())) {
            $username = $request->post->get('username');
            $password = $request->post->get('password');

            $authAdapter = new DbTable($db, 'users', 'username', 'password');
            $auth = new Auth($authAdapter);

            if ($auth->authorize($username, $password)) {
                $userRepository = new UserRepository($db);
                $user = $userRepository->fetchOneByUsername($username);
                unset($user->password);

                $request->session->set('user', $user);
                $referer = $request->session->has('ref_page')
                    ? $request->session->get('ref_page')
                    : $this->getView()->baseUrl();

                return $this->redirect($referer);
            } else {
                $this->getView()->assign('error', 'Credentials is not valid.');
            }
        }

        return $this->render('user/login.phtml', array(
            'username' => $request->post->get('username', '')
        ));
    }

    public function logoutAction()
    {
        $request = $this->getRequest();

        $referer = $request->session->has('ref_page')
            ? $request->session->get('ref_page')
            : $this->getView()->baseUrl();

        $request->session->remove('user');

        return $this->redirect($referer);
    }

    protected function isLoginFormValid($data)
    {
        if (empty($data['username']) || empty($data['password'])) {
            $this->getView()->assign('error', 'Incorrect input data.');
            return false;
        }
        return true;
    }

    /**
     * @TODO Need refactoring because of too big method size
     * @param $id
     * @return \Core\RedirectResponse
     */
    public function buyAction($id)
    {
        $request = $this->getRequest();
        $baseUrl = $this->getView()->baseUrl();
        $request->session->set('ref_page', $this->getRequest()->server->get('REQUEST_URI'));

        try {
            $article = new Article($id);
        } catch (\InvalidArgumentException $e) {
            $this->redirect($baseUrl);
        }

        # save article page to redirect after purchase
        $articleUrl = $baseUrl . '/article/' . $this->getView()->escape($id);
        $request->session->set('article_url', $articleUrl);

        if ($article->price == 0) {
            $request->session->getFlashBag()->set('warning', 'You cant\'t buy this article. It\'s FREE for you!');
            return $this->redirect($articleUrl);
        }

        $isLoggedUser = $this->isLoggedUser();

        if (false === $isLoggedUser) {
            $request->session->getFlashBag()->set('info', 'Please log in to proceed purchase.');
            return $this->redirect($baseUrl . '/login');
        }

        $user = $request->session->get('user');

        $userRepository = new UserRepository();
        $isInCollection = $userRepository->isInCollection($article->articleId, $user->userId);

        if ($isInCollection) {
            $request->session->getFlashBag()->set('warning', 'You\'ve already purchased this article. You can always find it in your collection.');
            $url = $request->session->has('article_url') ? $request->session->get('article_url') : $this->getView()->baseUrl();
            return $this->redirect($url);
        }

        $wallet = (float) $user->wallet;
        $price = (float) $article->price;

        # make purchase
        if ($wallet >= $price) {
            # begin transaction
            OrderRepository::beginTransaction();

            try {
                $order = new Order();
                $order->customerId = $user->userId;
                $order->articleId = $article->articleId;
                $order->price = $price;
                $order->orderDate = date('Y-m-d H:i:s');
                $order->save();
                $user->wallet = $wallet - $price;
                $user->save();
            } catch (\Exception $e) {
                # set $user amount in session to previous value
                $user->wallet = $wallet;

                # rollback transaction
                OrderRepository::rollbackTransaction();

                $request->session->getFlashBag()->set('danger', 'An error has occurred during purchase. Please try again later.');
                return $this->redirect($baseUrl);
            }
            # commit transaction
            OrderRepository::commitTransaction();

            $request->session->getFlashBag()->set('success', 'You\'ve purchased an article!');
        } else {
            $request->session->getFlashBag()->set('danger', 'You don\'t have enough money for purchase.');
        }

        $url = $request->session->has('article_url') ? $request->session->get('article_url') : $baseUrl . '/user/collection';
        return $this->redirect($url);

    }

    public function collectionAction()
    {
        $request = $this->getRequest();
        $isLoggedUser = $this->isLoggedUser();

        if (false === $isLoggedUser) {
            $request->session->getFlashBag()->set('info', 'Session expired.');
            return $this->redirect($this->getView()->baseUrl());
        }

        $user = $request->session->get('user');
        $ordersRepository = new OrderRepository($this->application->getResource('db'));
        $orders = $ordersRepository->fetchAllByUser($user->userId);

        return $this->render('user/collection.phtml', array(
            'orders' => $orders,
            'user' => $user
        ));
    }

    public function isLoggedUser()
    {
        $isLoggedUser = $this->getRequest()->session->has('user');
        return $isLoggedUser;
    }
    
    
    // =================== Actions for test purposes ======================= //
    
    public function setmoneyAction($amount)
    {
        if ($this->isLoggedUser()) {
            $user = $this->getRequest()->session->get('user');

            if (is_numeric($amount)) {
                $user->wallet = $amount;
                $this->getRequest()->session->getFlashBag()->set('success', 'Wallet amount was successfully changed.');
                $user->save();
            } else {
                $this->getRequest()->session->getFlashBag()->set('danger', 'Wrong input data. Wallet amount wasn\'t changed.');
            }
        } else {
            $this->getRequest()->session->getFlashBag()->set('warning', 'To do this action please log in first.');
        }

        return $this->redirect($this->getView()->baseUrl());
    }
    
    public function cleanCollectionAction()
    {
        if ($this->isLoggedUser()) {
            $user = $this->getRequest()->session->get('user');

            $db = $this->application->getResource('db');
            $deleteQuery = <<<SQL
              DELETE FROM orders
              WHERE orders.customerId = ?
SQL;
            $sth = $db->exec($deleteQuery, array($user->userId));

            if ($sth instanceof \PDOStatement) {
                $this->getRequest()->session->getFlashBag()->set('success', 'Users collection was successfully cleaned up.');
            }

        } else {
            $this->getRequest()->session->getFlashBag()->set('warning', 'To do this action please log in first.');
        }

        return $this->redirect($this->getView()->baseUrl());
    }
}