<?php

$start = microtime(true);

ini_set('display_errors', 'On');

require __DIR__ . '/../src/DiamondAndSquare.php';

$size = 3;

$gen = new MapGenerator\DiamondAndSquare();
$gen->setSize($size);
$gen->setPersistence(50);
$gen->setMapSeed('uniqueSeed');

if ($size > 3) {
    $gen->generate();
} else {
    print_r($gen->generate());
}

echo sprintf('Time: %s', round(microtime(true) - $start, 3));