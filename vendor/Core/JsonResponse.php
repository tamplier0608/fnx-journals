<?php

namespace Core;
use Core\Response\ResponseInterface;

/**
 * Class Response
 * @package vendor\Core
 */
class JsonResponse implements ResponseInterface
{
    protected $content;
    protected $statusCode;

    public function __construct(array $content = array(), $statusCode = 200)
    {
        $this->setContent($content);
    }

    /**
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param array $content
     */
    public function setContent(array $content)
    {
        $this->content = $content;
    }

    public function send()
    {
        $this->sendContent();
    }

    public function sendContent()
    {
        echo json_encode($this->content);
    }
}