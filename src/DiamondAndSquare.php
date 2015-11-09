<?php

namespace MapGenerator;

use Exception;
use InvalidArgumentException;
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
    private $maxOffset = 100;

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

    /**
     * Heightmap generation
     *
     * @param int       $preSize
     * @param int|float $offset
     * @param string    $mapHash
     *
     * @return $this
     */
    public function generate($preSize, $offset = null, $mapHash = null)
    {
        if (!is_int($preSize)) {
            throw new InvalidArgumentException(sprintf("preSize must be int, %s given", gettype($preSize)));
        }

        $this->size = pow(2, $preSize) + 1;
        $this->setMaxOffset($offset);
        $this->setMapHash($mapHash ?: uniqid());

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

        return $this;
    }

    /**
     * @return SplFixedArray
     */
    public function getMap()
    {
        return $this->terra;
    }

    /**
     * @param int       $size
     * @param int|float $maxOffset
     * @param string    $mapHash
     *
     * @return SplFixedArray
     */
    public static function generateAndGetMap($size, $maxOffset = null, $mapHash = null)
    {
        $map = new self();

        return $map->generate($size, $maxOffset, $mapHash)->getMap();
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
        $maxOffset = $this->getMaxOffset();

        //update hash for new "random" value
        $this->stepHash = md5($this->stepHash);
        //calculate value from hash (from -$maxOffset / 2 to $maxOffset / 2)
        $rand = -$maxOffset / 2 + intval(substr(md5($this->stepHash), -7), 16) % $maxOffset;

        return $stepSize / $this->size * $rand;
    }

    /**
     *
     * @return float
     */
    private function getMaxOffset()
    {
        return $this->maxOffset;
    }

    /**
     *
     * @param int|float $maxOffset
     */
    private function setMaxOffset($maxOffset)
    {
        if (!is_numeric($maxOffset)) {
            throw new InvalidArgumentException(sprintf("maxOffset must be numeric, %s given", gettype($maxOffset)));
        }

        if ($maxOffset === null) {
            $maxOffset = $this->size;
        } elseif ($maxOffset == 0) {
            throw new InvalidArgumentException("maxOffset should not be equal 0");
        }

        $this->maxOffset = abs($maxOffset);
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

}
