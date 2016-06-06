<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Repository\Authors;
use AppBundle\Entity\Repository\Categories;
use AppBundle\Entity\Repository\Comments;
use AppBundle\Entity\Repository\Tags;
use Core\Db\Entity;

/**
 * Class Articles
 * @package Entity
 */
class Article extends Entity
{
    protected static $table = 'articles';
    protected static $primaryKey = 'articleId';
    protected static $avoidSaving = array('authors', 'tags', 'comments', 'category');

    private $authors = array();
    private $tags = array();
    private $comments = array();
    private $category;

    public function getAuthors()
    {
        if (0 === count($this->authors)) {
            $authorRepository = new Authors();
            $this->authors = $authorRepository->fetchAllByArticle($this->articleId);
        }
        return $this->authors;
    }

    public function getTags()
    {
        if (0 === count($this->tags)) {
            $tagsRepository = new Tags();
            $this->tags = $tagsRepository->fetchAllByArticle($this->articleId);
        }
        return $this->tags;
    }

    public function getComments($limit)
    {
        if (0 === count($this->comments)) {
            $commentRepository = new Comments();
            $this->comments = $commentRepository->findBy(['articleId = ?'], [$this->articleId], $limit, $orderBy = 'date DESC');
        }
        return $this->comments;
    }

    public function getCategory()
    {
        if (null === $this->category) {
            $catRepository = new Categories();
            $this->category = $catRepository->find($this->categoryId);
        }
        return $this->category;
    }
}