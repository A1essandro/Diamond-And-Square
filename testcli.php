<?php

$start = microtime(true);

ini_set('display_errors', 'On');

require 'src/DiamondAndSquare.php';

$preSize = 10;
$maxOffset = 100;

$gen = new MapGenerator\DiamondAndSquare();
if ($preSize <= 3) {
    print_r($gen->generate($preSize, $maxOffset)->getMap()); //return float[][]
} else {
    $gen->generate($preSize, $maxOffset)->getMap();
}

//OR

//MapGenerator\DiamondAndSquare::generateAndGetMap($preSize, $maxOffset); //return float[][]

echo sprintf('Time: %s', microtime(true) - $start);