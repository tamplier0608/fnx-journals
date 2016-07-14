<?php

namespace Core\View\helpers;

class Head
{
    protected static $instance;
    protected $styles = array();
    protected $scripts = array();
    protected $title = '';

    public function head()
    {
        return self::getInstance();
    }
    
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    public function __toString() 
    {
        $echo = '';

        $echo .= $this->getStylesheets();
        $echo .= $this->getScripts();

        return $echo;
    }
    
    public function getStylesheets()
    {
        $echo = '';
        foreach ($this->styles as $style) {
            $echo .= $style ;
        }
        return $echo;
    }
    
    public function getScripts()
    {
        $echo = '';
        foreach ($this->scripts as $style) {
            $echo .= $style ;
        }
        return $echo;
    }

    public function addStylesheet($stylesheet)
    {
        $this->styles[] = "<link href=\"" . $stylesheet . "\" rel=\"stylesheet\" type=\"text/css\" />\n";
    }

    public function addScript($script)
    {
        $this->scripts[] = "<script src=\"" . $script . "\" type=\"text/javascript\"></script>\n";
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title) 
    {
        $this->title = $title;
    }
    
    public function startCaptureScript()
    {
        ob_start();
    }
    
    public function stopCaptureScript()
    {
        $echo = ob_get_clean();
        
        $this->scripts[] = "<script type=\"text/javascript\">\n" . $echo . "\n</script>\n";
    }
    
}