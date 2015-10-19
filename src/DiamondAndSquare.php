<?php

namespace MapGenerator;

/**
 * Diamond & Square algorithm
 *
*@author Alexander Yermakov
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

    public function __construct($size, $maxOffset = 100)
    {
        $this->size = pow(2, $size) + 1;
        $this->maxOffset = $maxOffset;
    }

    /**
     * Heightmap generation
     *
     * @return $this
     */
    public function generate()
    {
        for ($x = 0; $x < $this->size; $x++) {
            for ($y = 0; $y < $this->size; $y++) {
                $this->terra[$x][$y] = null;
            }
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

    public static function generateAndGetMap($size, $maxOffset = 100)
    {
        $map = new self($size, $maxOffset);
        return $map->generate()->getMap();
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
            rand(-$this->maxOffset / 2, $this->maxOffset / 2);
    }

    /**
     *
     * @return float
     */
    public function getMaxOffset()
    {
        return $this->maxOffset;
    }

    /**
     *
     * @param float $maxOffset
     */
    public function setMaxOffset($maxOffset)
    {
        $this->maxOffset = $maxOffset;
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
        return isset($this->terra[$x][$y]) && $this->terra[$x][$y] !== null
            ? $this->terra[$x][$y]
            : $this->getOffset($stepSize);
    }

}
