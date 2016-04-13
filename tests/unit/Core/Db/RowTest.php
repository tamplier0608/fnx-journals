<?php


class RowTest extends \PHPUnit_Framework_TestCase
{
    private $row;

    public function setUp()
    {
        $this->row = new \Page();
        $this->row->title = 'Test title';
        $this->row->created = '2016-04-13 14:42:49';
        $this->row->content = 'Page content';
    }

    /**
     * @covers Core\Db\Row::buildSaveQuery()
     */
    public function testBuildSaveQuery()
    {
        $method = $this->makeMethodAccessible($this->row, 'buildSaveQuery');
        $expected = 'INSERT INTO pages (title,created,content) VALUES ("Test title","2016-04-13 14:42:49","Page content") ON DUPLICATE KEY UPDATE title= "Test title",created= "2016-04-13 14:42:49",content= "Page content"';

        $query = $method->invoke($this->row);

        $this->assertEquals($expected, $query);
    }

    /**
     * @return ReflectionMethod
     */
    protected function makeMethodAccessible($object, $methodName)
    {
        $reflectionObject = new \ReflectionObject($object);
        $method = $reflectionObject->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @covers Core\Db\Row::buildDeleteQuery()
     */
    public function testBuildDeleteQuery()
    {
        $method = $this->makeMethodAccessible($this->row, 'buildDeleteQuery');
        $expected = 'DELETE FROM pages WHERE id = ?';

        $query = $method->invoke($this->row);

        $this->assertEquals($expected, $query);
    }

    /**
     * @covers Core\Db\Row::buildFetchQuery()
     */
    public function testBuildFetchQuery()
    {
        $method = $this->makeMethodAccessible($this->row, 'buildFetchQuery');
        $expected = 'SELECT * FROM pages WHERE id = ?';

        $query = $method->invoke($this->row);

        $this->assertEquals($expected, $query);
    }
}
