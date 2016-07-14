<?php

class ViewTest extends \PHPUnit_Framework_TestCase
{
    protected $view;

    public function setUp()
    {
        $this->view = new \Core\View(TEST_ROOT . '/fixtures/templates');
    }

    public function testFetch()
    {
        $content = $this->view->fetch('page.phtml', array());
        $page = $this->view->fetch('layout.phtml', array('title' => 'Title', 'content' => $content));
        $page = str_replace(array("\n", "\t", " "), '', $page);
        $expected = "<html><head><title>Title</title></head><body><h1>Pagecontent</h1><p>Loremipsum...</p></body></html>";
        
        $this->assertEquals($expected, $page);
    }

    public function render()
    {
        ob_start();
        $content = $this->view->render('page.phtml', array());
        $page = $this->view->render('layout.phtml', array('title' => 'Title', 'content' => $content));
        $result = ob_get_clean();
        $result = str_replace(array("\n", "\t", " "), '', $result);

        $expected = "<html><head><title>Title</title></head><body><h1>Pagecontent</h1><p>Loremipsum...</p></body></html>";
        $this->assertEquals($expected, $result);
    }
}
