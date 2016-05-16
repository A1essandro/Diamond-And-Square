<?php

ini_set('display_errors', 'On');
require_once __DIR__ . '/../src/DiamondAndSquare.php';
$gen = new MapGenerator\DiamondAndSquare();

$size = 8;

$gen->setPersistence(1.8);
$gen->setSize($size);
$gen->setMapSeed(uniqid());
$map = $gen->generate();

$image = imagecreatetruecolor($gen->getSize(), $gen->getSize());

$max = 0;
$min = PHP_INT_MAX;
for ($iy = 0; $iy < $gen->getSize(); $iy++) {
    for ($ix = 0; $ix < $gen->getSize(); $ix++) {
        $h = $map[$iy][$ix];
        if ($min > $h) {
            $min = $h;
        }
        if ($max < $h) {
            $max = $h;
        }
    }
}
$diff = $max - $min;

for ($iy = 0; $iy < $gen->getSize(); $iy++) {
    for ($ix = 0; $ix < $gen->getSize(); $ix++) {
        $h = 255 * ($map[$iy][$ix] - $min) / $diff;
        $color = imagecolorallocate($image, $h, $h, $h);
        imagesetpixel($image, $ix, $iy, $color);
    }
}

imagepng($image, 'visual.png');
imagedestroy($image);