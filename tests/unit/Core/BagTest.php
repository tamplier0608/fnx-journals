<?php

class BagTest extends PHPUnit_Framework_TestCase
{
    const BAG_DATA_PROPERTY_NAME = 'parameters';
    protected $batch;

    public function setUp()
    {
        $this->batch = new \Core\ParameterBag();
    }

    /**
     * @covers Core\ParameterBag::get()
     * @covers Core\ParameterBag::set()
     * @covers Core\ParameterBag::has()
     */
    public function testGet()
    {
        $this->batch->set('page', 1);
        $this->assertNotEmpty($this->getObjectProtectedProperty(self::BAG_DATA_PROPERTY_NAME, $this->batch));
        $this->assertEquals($this->batch->get('page'), 1);
        $this->assertEquals($this->batch->get('action', 'index'), 'index');
        $this->assertTrue($this->batch->has('page'));

        $this->batch->remove('page');
        $this->assertEmpty($this->getObjectProtectedProperty(self::BAG_DATA_PROPERTY_NAME, $this->batch));
    }

    protected function getObjectProtectedProperty($property, $object)
    {
        $refl = new \ReflectionClass($object);
        $propReflection = $refl->getProperty($property);
        $propReflection->setAccessible(true);

        return $propReflection->getValue($object);
    }

}
