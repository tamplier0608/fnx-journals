<?php

namespace Core;

/**
 * Class View
 * @package Core
 */
class View
{
    /**
     * @var array Directory paths for helper view classes
     */
    public static $helperPaths = array('Core/View/helpers');
    /**
     * @var Directory path where templates are placed
     */
    protected $templatesPath;
    /**
     * @var array Params assigned to template
     */
    protected $params;

    public function __construct($templatesPath)
    {
        $this->templatesPath = rtrim($templatesPath);
        $this->params = array();
    }

    public static function addHelperPath($helperPath)
    {
        static::$helperPaths[] = $helperPath;
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
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, array $params)
    {
        $helperClass = ucfirst($method);

        foreach (static::$helperPaths as $path) {
            $helperClass = str_replace('/', '\\', $path . '/' . ucfirst($method));

            if (class_exists($helperClass)) {
                break;
            }
        }

        $helperObj = new $helperClass();
        return $helperObj->$method($params);
    }
}