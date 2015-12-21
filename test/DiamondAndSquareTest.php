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
            array(7),
        );
    }

    public function providerSetSizeNotInt()
    {
        return array(
            array('a'),
            array(2.1),
            array(10.),
        );
    }

    public function providerSetInvalidPersistence()
    {
        return array(
            array('a'),
            array(null),
            array(array()),
        );
    }

    public function providerSetInvalidMapHash()
    {
        return array(
            array(123),
            array(null),
            array(new StdClass()),
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
     * @dataProvider providerSetInvalidMapHash
     * @expectedException InvalidArgumentException
     */
    public function testSetInvalidMapHash($mapHash)
    {
        $this->diamondSquare->setMapHash($mapHash);
    }

    public function testHashEquals()
    {
        $this->diamondSquare->setSize(4);
        $this->diamondSquare->setPersistence(100);

        //same hashes
        $mapHash = uniqid();
        $this->diamondSquare->setMapHash($mapHash);
        $map1 = $this->diamondSquare->generate();
        $map2 = $this->diamondSquare->generate();

        $this->assertEquals($map1, $map2);

        //different hashes
        $mapHash1 = uniqid() . '1';
        $mapHash2 = uniqid() . '2';

        $this->diamondSquare->setMapHash($mapHash1);
        $map1 = $this->diamondSquare->generate();
        $this->diamondSquare->setMapHash($mapHash2);
        $map2 = $this->diamondSquare->generate();

        $this->assertNotEquals($map1, $map2);
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