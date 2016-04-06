<?php

namespace Controller;

use Core\Application\Controller as CoreController;
use Core\HttpException;
use Entity\Article;
use Entity\Author;
use Entity\Category;
use Entity\Repository\Articles as ArticleRepository;
use Entity\Repository\Authors as AuthorRepository;
use Entity\Repository\Tags as TagRepository;
use Entity\Repository\Users as UserRepository;

class IndexController extends CoreController
{
    const ARTICLES_COUNT = 3;

    public function postDispatch()
    {
        $this->getRequest()->session->set('ref_page', $this->getRequest()->server->get('REQUEST_URI'));
    }

    public function indexAction()
    {
        $articleRepository = new ArticleRepository($this->db);
        $page = (int) $this->getRequest()->query->get('page', 1);
        $pagination = $this->getIndexPagination($articleRepository, $page, self::ARTICLES_COUNT);

        $pagination['data'] = $this->prepareArticlesListData($pagination['data']);

        return $this->render('index/index.phtml', array(
            'pagination' => $pagination,
            'isLoggedUser' => $this->isLoggedUser()
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

    public function showArticleAction($id)
    {
        try {
            $article = new Article($id);
            $authorRepository = new AuthorRepository();
            $tagRepository = new TagRepository();

            # add category
            if (null !== $article->categoryId) {
                $category = new Category($article->categoryId);
                $article->category = $category;
            }

            # add authors
            $authors = $authorRepository->fetchAllByArticle($article->articleId);
            $article->authors = array();
            foreach ($authors as $author) {
                $article->authors[] = $author;
            }

            # add tags
            $tags = $tagRepository->fetchAllByArticle($article->articleId);
            $article->tags = array();
            foreach ($tags as $tag) {
                $article->tags[] = $tag;
            }

        } catch(\InvalidArgumentException $e) {
            throw new HttpException('Article is not found.', 404);
        }

        $isLoggedUser = $this->isLoggedUser();

        if ($isLoggedUser) {
            $userRepository = new userRepository();
            $user = $this->getRequest()->session->get('user');
            $article->isPurchased = $userRepository->isInCollection($article->articleId, $user->userId);
        }

        return $this->render('index/show_article.phtml', array(
            'article' => $article,
            'isFreeArticle' => ($article->price == 0),
            'isLoggedUser' => $isLoggedUser,
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

        $articlesRowset = $articleRepository->fetchAllByAuthorId($author->authorId);
        $articles = $this->prepareArticlesListData($articlesRowset);

        return $this->render('index/author_page.phtml', array(
            'author' => $author,
            'articles' => $articles,
            'isLoggedUser' => $this->isLoggedUser()
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
        $pagination['data'] = $this->prepareArticlesListData($pagination['data']);

        return $this->render('index/category_page.phtml', array(
            'category' => $category,
            'pagination' => $pagination,
            'isLoggedUser' => $this->isLoggedUser()
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

    /**
     * @param $articlesRowset
     * @return array
     */
    protected function prepareArticlesListData($articlesRowset)
    {
        $tagRepository = new TagRepository();
        $authorRepository = new AuthorRepository();
        $userRepository = new UserRepository();

        $articles = array();

        foreach ($articlesRowset as $article) {
            # add category
            if (null !== $article->categoryId) {
                $category = new Category($article->categoryId);
                $article->category = $category;
            }

            # add authors
            $authors = $authorRepository->fetchAllByArticle($article->articleId);
            $article->authors = array();
            foreach ($authors as $author) {
                $article->authors[] = $author;
            }

            # add tags
            $tags = $tagRepository->fetchAllByArticle($article->articleId);
            $article->tags = array();
            foreach ($tags as $tag) {
                $article->tags[] = $tag;
            }

            if ($this->isLoggedUser()) {
                $user = $this->getRequest()->session->get('user');
                $article->isPurchased = $userRepository->isInCollection($article->articleId, $user->userId);
            }

            $articles[] = $article;
        }
        return $articles;
    }

    /**
     * @return bool
     */
    public function isLoggedUser()
    {
        return $this->getRequest()->session->has('user');
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
}