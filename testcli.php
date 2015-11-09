<?php

$start = microtime(true);

ini_set('display_errors', 'On');

require 'src/DiamondAndSquare.php';

$preSize = 10;
$maxOffset = 100;
$identifier = '1hCaHs5hZ';

$gen = new MapGenerator\DiamondAndSquare();
if ($preSize <= 3) {
    $mapInfo = $gen->generate($preSize, $maxOffset, $identifier);
    $mapHash = $mapInfo->getMapHash(); //Map unique identifier (return $identifier)
    $map = $mapInfo->getMap(); //get heights
} else {
    $gen->generate($preSize, $maxOffset, $identifier)->getMap();
}

//OR

//MapGenerator\DiamondAndSquare::generateAndGetMap($preSize, $maxOffset, $identifier);

echo sprintf('Time: %s', round(microtime(true) - $start, 3));