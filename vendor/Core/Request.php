<?php

namespace Core;

/**
 * Class Request
 * @package Core
 */
class Request
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_XMLHTTPREQUEST = 'XMLHttpRequest';

    public $query;
    public $post;
    public $server;
    public $request;
    public $session;
    public $cookie;

    protected function __construct(
        array $query = array(),
        array $post = array(),
        array $server = array(),
        array $request = array(),
        array $cookie = array()
    ) {
        $this->query = new ParameterBag($query);
        $this->post = new ParameterBag($post);
        $this->server = new ServerBag($server);
        $this->request = new ParameterBag($request);
        $this->cookie = new ParameterBag($cookie);
        $this->session = new Session();
    }

    public static function createFromGlobals()
    {
        return new static($_GET, $_POST, $_SERVER, $_REQUEST, $_COOKIE);
    }

    public function isPost()
    {
        return $this->server->get('REQUEST_METHOD') === self::METHOD_POST;
    }

    public function isXmlHttpRequest()
    {
        return $this->server->get('HTTP_X_REQUESTED_WITH') === self::METHOD_XMLHTTPREQUEST;
    }

    public function getBaseUrl()
    {
        $requestUri = $this->getUri();
        $requestScriptName = !$this->server->has('PHP_SELF') ? $this->server->get('PHP_SELF') : $this->server->get('SCRIPT_NAME');
        $scriptBaseName = basename($requestScriptName);

        $scriptPath = substr($requestScriptName, 0, -strlen($scriptBaseName)); # remove basename from script name

        $uriSegments = explode('/', trim($requestUri, '/'));
        $scriptPathSegments = explode('/', trim($scriptPath, '/'));

        $common = array();

        # looking for common parts in script path and URI
        foreach ($uriSegments as $segment) {
            if ($segment === array_shift($scriptPathSegments)) {
                $common[] = $segment;
            }
        }

        return '/' . implode('/', $common);
    }

    public function getUri()
    {
        return $this->server->get('REQUEST_URI');
    }
}