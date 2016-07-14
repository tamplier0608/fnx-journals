<?php

namespace Core;

use Core\Response\ResponseInterface;

/**
 * Class RedirectResponse
 * @package Core
 */
class RedirectResponse implements ResponseInterface
{
    protected $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function send()
    {
        header('Location: ' . $this->url);
        echo "<meta http-equiv=\"referer\" content=\"1;URL={$this->url}\" />";
        echo "Please click <a href=\"{$this->url}\">here</a> to proceed.";
    }
}