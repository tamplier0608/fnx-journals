<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Repository\Articles;
use Core\Db\Entity;

/**
 * Class Articles
 * @package Entity
 */
class Tag extends Entity
{
    protected static $table = 'tags';
    protected static $primaryKey = 'tagId';
    protected static $avoidSaving = array('articles');
    
    protected $articles = array();

    public function getArticles()
    {
        if (0 === count($this->articles)) {
            $articleRepository = new Articles();
            $this->articles = $articleRepository->fetchAllByTag($this->tagId);
        }
        return $this->articles;
    }
}