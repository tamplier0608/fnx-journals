<?php

namespace Core;
use Core\Response\ResponseInterface;

/**
 * Class Response
 * @package vendor\Core
 */
class Response implements ResponseInterface
{
    protected $content;
    protected $statusCode;

    public function __construct($content = '', $statusCode = 200)
    {
        $this->setContent($content);
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    public function send()
    {
        $this->sendContent();
    }

    public function sendContent()
    {
        echo $this->content;
    }
}