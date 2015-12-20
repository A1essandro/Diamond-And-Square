<?php

namespace MapGenerator;

use Exception;
use InvalidArgumentException;
use LogicException;
use SplFixedArray;

/**
 * Diamond & Square algorithm
 *
 * @author Alexander Yermakov
 */
class DiamondAndSquare
{

    /**
     * Real size (computed in the generate() method)
     *
     * @var int
     */
    private $size;

    /**
     * @var SplFixedArray
     */
    private $terra;

    /**
     * @var float
     */
    private $persistence;

    /**
     * Map unique hash
     *
     * @var string
     */
    private $mapHash;

    /**
     * @var
     */
    private $stepHash;

    public function __construct()
    {
        //empty
    }

    public function setSize($size)
    {
        if (!is_int($size)) {
            throw new InvalidArgumentException(sprintf("preSize must be int, %s given", gettype($size)));
        }

        $this->size = pow(2, $size) + 1;
    }

    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get identity hash
     *
     * @return string
     */
    public function getMapHash()
    {
        return $this->mapHash;
    }

    /**
     * Set unique hash (for identity)
     *
     * @param string $mapHash
     */
    public function setMapHash($mapHash)
    {
        $this->mapHash = $this->stepHash = $mapHash;
    }

    /**
     *
     * @return int|float
     */
    public function getPersistence()
    {
        return $this->persistence;
    }

    /**
     *
     * @param int|float $persistence
     */
    public function setPersistence($persistence)
    {
        if (!is_numeric($persistence)) {
            throw new InvalidArgumentException(sprintf("maxOffset must be numeric, %s given", gettype($persistence)));
        }

        $this->persistence = abs($persistence);
    }

    /**
     * Heightmap generation
     *
     * @return SplFixedArray[]
     */
    public function generate()
    {
        if(empty($this->mapHash))
            $this->setMapHash(uniqid());

        if(!$this->getPersistence())
            throw new LogicException('Persistence must be set');

        if(!$this->getSize())
            throw new LogicException('Size must be set');

        $this->terra = new SplFixedArray($this->size);
        for ($x = 0; $x < $this->size; $x++) {
            $this->terra[$x] = new SplFixedArray($this->size);
        }

        $last = $this->size - 1;
        $this->terra[0][0] = $this->getOffset($this->size);
        $this->terra[0][$last] = $this->getOffset($this->size);
        $this->terra[$last][0] = $this->getOffset($this->size);
        $this->terra[$last][$last] = $this->getOffset($this->size);

        $this->divide($this->size);

        return $this->terra;
    }

    /**
     * recursive division
     *
     * @param $stepSize
     */
    private function divide($stepSize)
    {
        $half = floor($stepSize / 2);

        if ($half < 1) {
            return;
        }

        for ($x = $half; $x < $this->size; $x += $stepSize) {
            for ($y = $half; $y < $this->size; $y += $stepSize) {
                $this->square($x, $y, $half, $this->getOffset($stepSize));
            }
        }

        $this->divide($half);
    }

    /**
     * Definition of height middle point in square
     *
     * @param $x      int
     * @param $y      int
     * @param $size   int
     * @param $offset float
     */
    private function square($x, $y, $size, $offset)
    {
        $a = $this->getCellHeight($x - $size, $y - $size, $size);
        $b = $this->getCellHeight($x + $size, $y + $size, $size);
        $c = $this->getCellHeight($x - $size, $y + $size, $size);
        $d = $this->getCellHeight($x + $size, $y - $size, $size);

        $average = ($a + $b + $c + $d) / 4;
        $this->terra[$x][$y] = $average + $offset;

        $this->diamond($x, $y - $size, $size, $this->getOffset($size));
        $this->diamond($x - $size, $y, $size, $this->getOffset($size));
        $this->diamond($x, $y + $size, $size, $this->getOffset($size));
        $this->diamond($x + $size, $y, $size, $this->getOffset($size));
    }

    /**
     * Definition of height middle point in diamond
     *
     * @param $x      int
     * @param $y      int
     * @param $size   int
     * @param $offset float
     */
    private function diamond($x, $y, $size, $offset)
    {
        $a = $this->getCellHeight($x, $y - $size, $size);
        $b = $this->getCellHeight($x, $y + $size, $size);
        $c = $this->getCellHeight($x - $size, $y, $size);
        $d = $this->getCellHeight($x + $size, $y, $size);

        $average = ($a + $b + $c + $d) / 4;

        $this->terra[$x][$y] = $average + $offset;
    }

    /**
     * Getting random displacement
     *
     * @param float $stepSize
     *
     * @return float
     */
    private function getOffset($stepSize)
    {
        $maxOffset = $this->getPersistence();

        //update hash for new "random" value
        $this->stepHash = md5($this->stepHash);
        //calculate value from hash (from 0 to $maxOffset)
        $rand = intval(substr(md5($this->stepHash), -7), 16) % $maxOffset;

        return (float) $stepSize / $this->size * $rand;
    }

    /**
     * return point height if point exists, else random height
     *
     * @param $x
     * @param $y
     * @param $stepSize
     *
     * @return double
     */
    private function getCellHeight($x, $y, $stepSize = 0)
    {
        try {
            return $this->terra[$x][$y];
        } catch (Exception $e) {
            return $this->getOffset($stepSize);
        }
    }

}
