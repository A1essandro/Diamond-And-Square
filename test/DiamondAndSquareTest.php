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
            array(array(1, 2, 3)),
            array(null),
            array(new stdClass()),
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
        $this->diamondSquare->setMapSeed($mapHash);
    }

    public function testHashEquals()
    {
        $this->diamondSquare->setSize(1);
        $this->diamondSquare->setPersistence(100);

        //same hashes
        $mapHash = uniqid();
        $this->diamondSquare->setMapSeed($mapHash);
        $map1 = $this->diamondSquare->generate();
        $map2 = $this->diamondSquare->generate();

        $this->assertEquals(self::expandMap($map1), self::expandMap($map2));
        $this->assertEquals($mapHash, $this->diamondSquare->getMapSeed());
    }

    public function testDifferentHashes()
    {
        $mapHash1 = uniqid() . '1';
        $mapHash2 = uniqid() . '2';

        $this->diamondSquare->setSize(3);
        $this->diamondSquare->setPersistence(100);

        $this->diamondSquare->setMapSeed($mapHash1);
        $map1 = $this->diamondSquare->generate();
        $this->diamondSquare->setMapSeed($mapHash2);
        $map2 = $this->diamondSquare->generate();

        $this->assertNotEquals(self::expandMap($map1), self::expandMap($map2));
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
        $this->diamondSquare->setPersistence(10);
        $map = $this->diamondSquare->generate();

        $points = array();
        foreach ($map as $line) {
            foreach ($line as $point) {
                $points[] = $point;
            }
        }

        $this->assertContainsOnly('float', $points);
    }

    public function testGenerationWithOptions()
    {
        $this->diamondSquare->generate(
            array(
                DiamondAndSquare::SIZE        => 7,
                DiamondAndSquare::PERSISTENCE => 0.756,
                DiamondAndSquare::MAP_SEED    => microtime()
            )
        );
    }

    public function testMixedOptionsGeneration()
    {
        $this->diamondSquare->setSize(7);
        $this->diamondSquare->generate(
            array(
                DiamondAndSquare::PERSISTENCE => 0.756,
                DiamondAndSquare::MAP_SEED    => microtime()
            )
        );
    }

    public function testDefaultValues()
    {
        $result = $this->diamondSquare->generate();
        $this->assertNotEmpty($result);
    }

    #endregion

    private static function expandMap($map)
    {
        $expandPoints = array();
        foreach ($map as $line) {
            foreach ($line as $point) {
                $expandPoints[] = $point;
            }
        }

        return $expandPoints;
    }

}