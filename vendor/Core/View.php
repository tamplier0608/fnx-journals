<?php

namespace Core;

/**
 * Class View
 * @package Core
 */
class View
{
    /**
     * @var Directory path where templates are placed
     */
    protected $templatesPath;

    /**
     * @var array Params assigned to template
     */
    protected $params;

    /**
     * @var array Directory paths for helper view classes
     */
    protected $helperPaths;

    public function __construct($templatesPath)
    {
        $this->templatesPath = rtrim($templatesPath);
        $this->params = array();
        $this->helperPaths = array('Core/View/helpers');
    }

    /**
     * Assign value to specified view parameter
     *
     * @param $key
     * @param $value
     * @return View
     */
    public function assign($key, $value)
    {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * Return rendered template as string
     *
     * @param $template
     * @param array $params
     * @return mixed
     */
    public function fetch($template, array $params = array())
    {
        $this->params = array_merge($this->params, $params);

        extract($this->params, EXTR_OVERWRITE);

        ob_start();
        include $this->templatesPath . '/' . $template;
        return ob_get_clean();
    }

    /**
     * Render template
     *
     * @param $template
     * @param array $params
     */
    public function render($template, array $params = array())
    {
        echo $this->fetch($template, $params);
    }

    /**
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, array $params)
    {
        $helperClass = ucfirst($method);

        foreach ($this->helperPaths as $path) {
            $helperClass = str_replace('/', '\\', $path . '/' . $helperClass);
            if (class_exists($helperClass)) {
                break;
            }
        }

        $helperObj = new $helperClass();
        return $helperObj->$method($params);
    }
}