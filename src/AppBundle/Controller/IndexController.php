<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Author;
use AppBundle\Entity\Category;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Repository\Articles;
use AppBundle\Entity\Repository\Articles as ArticleRepository;
use AppBundle\Entity\Repository\Comments;
use AppBundle\Entity\Repository\Users as UserRepository;
use AppBundle\Entity\Tag;
use Core\Application\Controller as CoreController;
use Core\Application\Exception;
use Core\HttpException;
use Core\JsonResponse;
use Core\Response;

class IndexController extends CoreController
{
    const ARTICLES_COUNT = 3;
    const COMMENTS_COUNT = 10;

    public function postDispatch()
    {
        $exceptions = array('comment/new');
        $requestUri = $this->getRequest()->server->get('REQUEST_URI');

        foreach ($exceptions as $e) {
            if (false !== strpos($requestUri, $e)) {
                return;
            }
        }
        $this->getRequest()->session->set('ref_page', $requestUri);
    }

    public function indexAction()
    {
        $articleRepository = new ArticleRepository($this->db);
        $page = (int) $this->getRequest()->query->get('page', 1);
        $pagination = $this->getIndexPagination($articleRepository, $page, self::ARTICLES_COUNT);

        return $this->render('index/index.phtml', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * @param ArticleRepository $repository
     * @param $page
     * @param $countPerPage
     * @throws HttpException If page number is more than count of pages
     * @return mixed
     */
    public function getIndexPagination(ArticleRepository $repository, $page, $countPerPage)
    {
        $countAll = (int)$repository->countAll();
        $paginationData = $this->getPaginationParams($page, $countPerPage, $countAll);
        $limit = $paginationData['start'] . ',' . $countPerPage;
        $rowSet = $repository->fetchAll($limit);

        return array_merge($paginationData, array('data' => $rowSet));
    }

    /**
     * @param $page
     * @param $countPerPage
     * @return array
     * @throws HttpException
     */
    public function getPaginationParams($page, $countPerPage, $countAll)
    {
        $countPages = (int) ceil($countAll / $countPerPage);

        if ($countPages && $page > $countPages) {
            throw new HttpException('Page not found', 404);
        }

        $start = ($page - 1) * $countPerPage;

        if ($start <= 0) {
            $start = 0;
        }

        $next = false;
        $prev = false;

        if ($page < $countPages && $countPages > 1) {
            $next = $page + 1;
        }

        if ($page > 1) {
            $prev = $page - 1;
        }

        return array(
            'start' => $start,
            'countPerPage' => $countPerPage,
            'countPages' => $countPages,
            'countAll' => $countAll,
            'next' => $next,
            'prev' => $prev
        );
    }

    public function showArticleAction($id)
    {
        try {
            $article = new Article($id);
        } catch(\InvalidArgumentException $e) {
            throw new HttpException('Article is not found.', 404);
        }

        if ($this->getRequest()->session->has('user')) {
            $userRepository = new userRepository();
            $user = $this->getRequest()->session->get('user');
            $article->isPurchased = $userRepository->isInCollection($article->articleId, $user->userId);
        }

        $commentRepository = new Comments();
        $commentsCount = $commentRepository->countAll(['articleId = ?'], [$id]);

        return $this->render('index/show_article.phtml', array(
            'article' => $article,
            'comments' => $article->getComments('0, ' . self::COMMENTS_COUNT),
            'isFreeArticle' => ($article->price == 0),
            'commentsCount' => $commentsCount
        ));
    }

    public function authorPageAction($id)
    {
        $articleRepository = new ArticleRepository();

        try {
            $author = new Author($id);
        } catch(\InvalidArgumentException $e) {
            throw new HttpException('Author is not found.', 404);
        }

        $articles = $articleRepository->fetchAllByAuthorId($author->authorId);

        return $this->render('index/author_page.phtml', array(
            'author' => $author,
            'articles' => $articles,
        ));
    }

    public function categoryPageAction($id)
    {
        $articleRepository = new ArticleRepository();

        try {
            $category = new Category($id);
        } catch(\InvalidArgumentException $e) {
            throw new HttpException('Category is not found.', 404);
        }

        $page = (int) $this->getRequest()->query->get('page', 1);
        $pagination = $this->getCategoryPagination($articleRepository, $category->categoryId, $page, self::ARTICLES_COUNT);

        return $this->render('index/category_page.phtml', array(
            'category' => $category,
            'pagination' => $pagination,
        ));
    }

    /**
     * @param ArticleRepository $repository
     * @param $page
     * @param $countPerPage
     * @throws HttpException If page number is more than count of pages
     * @return mixed
     */
    public function getCategoryPagination(ArticleRepository $repository, $categoryId, $page, $countPerPage)
    {
        $countAll = (int) $repository->countAllInCategory($categoryId);
        $paginationData = $this->getPaginationParams($page, $countPerPage, $countAll);
        $limit = $paginationData['start'] . ',' . $countPerPage;
        $rowSet = $repository->fetchAllByCategory($categoryId, $limit);

        return array_merge($paginationData, array('data' => $rowSet));
    }

    public function tagPageAction($id)
    {
        try {
            $tag = new Tag($id);
        } catch(\InvalidArgumentException $e) {
            throw new HttpException('Tag is not found.', 404);
        }

        $articles = $tag->getArticles();

        return $this->render('index/tag_page.phtml', array(
            'tag' => $tag,
            'articles' => $articles
        ));
    }

    public function addCommentAction()
    {
        $userId = $this->request->post->get('user_id');
        $articleId = $this->request->post->get('article_id');
        $message = $this->request->post->get('message');

        $error = $this->validateForm($userId, $articleId, $message);

        if (!empty($error)) {
            return new JsonResponse(array('success' => 0, 'message' => $error));
        }

        try {
            $comment = new Comment();
            $comment->userId = $userId;

            $now = new \DateTime();
            $comment->date = $now->format('Y-m-d H:i:s');

            $comment->text = $message;
            $comment->articleId = $articleId;
            $comment->save();

            $result = array('success' => 1, 'comment' => array(
                'username' => $comment->getUser()->username,
                'date' => $comment->date,
                'message' => $comment->text,
            ));

        } catch (Exception $e) {
            $result = array('success' => 0, 'message' => 'Comment wasn\'t saved. Please try again');
        }

        if ($this->request->isXmlHttpRequest()) {
            return new JsonResponse($result);
        }
        return $this->redirect($this->request->session->get('ref_page'));
    }

    /**
     * @param $userId
     * @param $message
     * @return string
     */
    protected function validateForm($userId, $articleId, $message)
    {
        $error = '';

        if (null === $userId) {
            $error = 'Session has been expired.';
        }

        if (null === $message) {
            $error = 'Message should be filled!';
            return $error;
        }

        if (null === $articleId) {
            $error = 'Article ID wasn\'t specified.';
            return $error;
        }

        return $error;
    }

    public function loadCommentsAction()
    {
        if ($this->request->isPost()) {
            $articleId = $this->request->post->get('article');
            $start = $this->request->post->get('start');

            $articleRepository = new Articles();
            $article = $articleRepository->find($articleId);
            $comments = $article->getComments($start . ', ' . self::COMMENTS_COUNT);

            foreach ($comments as $index => $comment) {
                $comment->user = $comment->getUser()->username;
            }

            return new JsonResponse(array('comments' => $comments));

        } else {
            return new Response('Wrong action method.', 500);
        }
    }
}