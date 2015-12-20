<?php

$start = microtime(true);

ini_set('display_errors', 'On');

require __DIR__ . '/src/DiamondAndSquare.php';

$size = 2;

$gen = new MapGenerator\DiamondAndSquare();
$gen->setSize($size);
$gen->setPersistence(200);
$gen->setMapHash('1hCaHs5hZ');

if ($size > 3) {
    $gen->generate();
} else {
    print_r($gen->generate());
}

echo sprintf('Time: %s', round(microtime(true) - $start, 3));