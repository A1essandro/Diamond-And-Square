<?php

namespace MapGenerator;

/**
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
     * разница между самой высокой и самой низкой точкой на карте
     *
     * @var float
     */
    private $maxOffset = 100;

    public function __construct($size, $maxOffset = 100)
    {
        $this->size = pow(2, $size) + 1;
        $this->maxOffset = $maxOffset;
    }

    public function generate()
    {
        for ($x = 0; $x < $this->size; $x++) {
            for ($y = 0; $y < $this->size; $y++) {
                $this->terra[$x][$y] = null;
            }
        }

        $this->terra[0][0] = $this->getOffset($this->size);
        $this->terra[0][$this->size - 1] = $this->getOffset($this->size);
        $this->terra[$this->size - 1][0] = $this->getOffset($this->size);
        $this->terra[$this->size - 1][$this->size - 1] = $this->getOffset($this->size);

        //основная часть алгоритма тут:
        $this->divide($this->size);
    }

    public function getMap()
    {
        return $this->terra;
    }

    public static function generateAndGetMap($size, $maxOffset = 100)
    {
        $map = new self($size, $maxOffset);
        $map->generate();
        return $map->getMap();
    }

    /**
     * Рекурсивное деление карты
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
     * Определение высоты клетки в центре квадрата
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
     * Определение высоты клетки в центре ромба
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
     * Получение случайного изменения высоты для точки
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
     * Получить максимальную разницу между самой выскокой и самой низкой точкой
     * изначально генерируемой карты высот. Полученный в итоге слой может
     * не включать в себя эти точки
     *
     * @return float
     */
    public function getMaxOffset()
    {
        return $this->maxOffset;
    }

    /**
     * Задать максимальную разницу между самой выскокой и самой низкой точкой
     * изначально генерируемой карты высот. Полученный в итоге слой может
     * не включать в себя эти точки
     *
     * @param float $maxOffset
     */
    public function setMaxOffset($maxOffset)
    {
        $this->maxOffset = $maxOffset;
    }

    /**
     * Возвращает высоту клетки, если ее нет, случайную высоту в зависимости от размера текущего шага
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
            : rand(-$stepSize, $stepSize);
    }

}
