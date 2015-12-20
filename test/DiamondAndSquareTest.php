<?php


use MapGenerator\DiamondAndSquare;

require_once __DIR__ . '/../vendor/autoload.php';

class DiamondAndSquareTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DiamondAndSquare|null
     */
    protected $diamondSquare;

    protected function setUp()
    {
        $this->diamondSquare = new DiamondAndSquare();
    }

    protected function tearDown()
    {
        $this->diamondSquare = null;
    }

    #region DataProviders

    public function providerSetSize()
    {
        return array(
            array(2),
            array(4),
            array(7)
        );
    }

    public function providerSetSizeNotInt()
    {
        return array(
            array('a'),
            array(2.1),
            array(10.)
        );
    }

    public function providerSetInvalidPersistence()
    {
        return array(
            array('a'),
            array(null),
            array(array())
        );
    }

    #endregion

    #region Tests

    /**
     * @dataProvider providerSetSize
     */
    public function testSetSize($size)
    {
        $this->diamondSquare->setPersistence(100);
        $this->diamondSquare->setSize($size);

        $realSize = $this->diamondSquare->getSize();

        $this->assertEquals($realSize, count($this->diamondSquare->generate()));
    }

    /**
     * @dataProvider providerSetSizeNotInt
     * @expectedException InvalidArgumentException
     */
    public function testSetSizeNotInt($sizeToSet)
    {
        $this->diamondSquare->setSize($sizeToSet);
    }

    /**
     * @dataProvider providerSetInvalidPersistence
     * @expectedException InvalidArgumentException
     */
    public function testSetInvalidPersistence($persistence)
    {
        $this->diamondSquare->setPersistence($persistence);
    }

    public function testContains()
    {
        $this->diamondSquare->setSize(5);
        $this->diamondSquare->setPersistence(100);
        $map = $this->diamondSquare->generate();

        $points = array();
        foreach ($map as $line) {
            foreach ($line as $point) {
                $points[] = $point;
            }
        }

        $this->assertContainsOnly('float', $points);
    }

    #endregion

}