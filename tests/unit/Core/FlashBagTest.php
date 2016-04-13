<?php

class FlashBagTest extends PHPUnit_Framework_TestCase
{
    protected $flashBag;
    protected $sessionKey;
    protected $flashesKey;
    
    public function setUp()
    {
        $this->flashBag = new \Core\FlashBag(new \Core\Session());
        $this->sessionKey = \Core\Session::SESSION_DEFAULT_STORAGE_KEY;
        $this->flashesKey = \Core\FlashBag::FLASHBAG_SESSION_KEY;
    }

    /**
     * @covers \Core\FlashBag::set()
     */
    public function testSet()
    {
        $this->flashBag->set('notice', 'over');

        $this->assertNotEmpty($_SESSION[$this->sessionKey][$this->flashesKey]);
        $this->assertArrayHasKey('notice', $_SESSION[$this->sessionKey][$this->flashesKey]);
        $this->assertEquals('over', $_SESSION[$this->sessionKey][$this->flashesKey]['notice']);
    }

    /**
     * @covers \Core\FlashBag::get()
     */
    public function testGet()
    {
        $this->flashBag->set('notice', 'over');
        $notice = $this->flashBag->get('notice');

        $this->assertArrayHasKey($this->flashesKey, $_SESSION[$this->sessionKey]);
        $this->assertNotEmpty($_SESSION[$this->sessionKey][$this->flashesKey]);

        $notice = $this->flashBag->get('notice', null, $remove = true);

        $this->assertArrayHasKey($this->flashesKey, $_SESSION[$this->sessionKey]);
        $this->assertEmpty($_SESSION[$this->sessionKey][$this->flashesKey]);

    }
}
