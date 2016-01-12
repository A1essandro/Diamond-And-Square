<?php

$start = microtime(true);

ini_set('display_errors', 'On');

require __DIR__ . '/../src/DiamondAndSquare.php';

$size = 3;

$gen = new MapGenerator\DiamondAndSquare();
$gen->setSize($size);
$gen->setPersistence(50);
$gen->setMapSeed('uniqueSeed');

$memStart = memory_get_usage();
if ($size > 3) {
    $gen->generate();
} else {
    print_r($gen->generate());
}

echo sprintf('Memory Limit: %s', ini_get('memory_limit')) . PHP_EOL;
echo sprintf('Memory Usage: %s', round((memory_get_usage() - $memStart) / 1024 / 1024, 2)) . 'M' . PHP_EOL;
echo sprintf('Memory Peak Usage: %s', round(memory_get_peak_usage() / 1024 / 1024, 2)) . 'M' . PHP_EOL;

echo sprintf('Time: %s', round(microtime(true) - $start, 3)) . PHP_EOL;