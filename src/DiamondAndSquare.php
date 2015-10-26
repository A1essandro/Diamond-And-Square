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
     *
     * @var int
     */
    private $size;

    private $terra = array(array());

    /**
     *
     * @var float
     */
    private $maxOffset = 100;

    public function __construct()
    {
        //empty
    }

    /**
     * Heightmap generation
     *
     * @param int       $preSize
     * @param int|float $offset
     *
     * @return $this
     */
    public function generate($preSize, $offset = null)
    {
        $this->size = pow(2, $preSize) + 1;
        $this->setMaxOffset($offset);

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

    public function getMap()
    {
        return $this->terra;
    }

    public static function generateAndGetMap($size, $maxOffset = null)
    {
        $map = new self();
        return $map->generate($size, $maxOffset)->getMap();
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
        return $stepSize / $this->size *
        rand(-$this->getMaxOffset() / 2, $this->getMaxOffset() / 2);
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
        if(!is_numeric($maxOffset))
            throw new InvalidArgumentException("maxOffset must be numeric");

        if($maxOffset === null)
            $maxOffset = $this->size;
        elseif($maxOffset == 0)
            throw new InvalidArgumentException("maxOffset should not be equal 0");

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

}
