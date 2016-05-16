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
    private $mapSeed;

    /**
     * @var number
     */
    private $floatSeed = 0;

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
     * @deprecated
     * @return string|number
     */
    public function getMapHash()
    {
        return $this->getMapSeed();
    }

    /**
     * Set unique hash (for identity)
     *
     * @deprecated
     *
     * @param string|number $mapHash
     */
    public function setMapHash($mapHash)
    {
        $this->setMapSeed($mapHash);
    }

    /**
     *
     * @param string|number $seed
     */
    public function setMapSeed($seed)
    {
        if (!is_string($seed) && !is_numeric($seed)) {
            throw new InvalidArgumentException(sprintf("Seed must be string or number, %s given", gettype($seed)));
        }

        $this->mapSeed = $seed;
        $this->floatSeed = is_numeric($seed) ? $seed : intval(substr(md5($seed), -8), 16);
    }

    /**
     * @return string
     */
    public function getMapSeed()
    {
        return $this->mapSeed;
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
        if (empty($this->mapSeed)) {
            $this->setMapSeed(microtime(true));
        }

        if (!$this->getPersistence()) {
            throw new LogicException('Persistence must be set');
        }

        if (!$this->getSize()) {
            throw new LogicException('Size must be set');
        }

        $this->terra = new SplFixedArray($this->size);
        for ($x = 0; $x < $this->size; $x++) {
            $this->terra[$x] = new SplFixedArray($this->size);
        }

        mt_srand($this->floatSeed * $this->size);

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
        $stepOffset = $stepSize / $this->size * mt_rand(-$this->size, $this->size);
        $sign = $stepOffset < 0 ? -1 : 1;
        return $sign * pow(abs($stepOffset), 1 / sqrt($this->getPersistence()));
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
