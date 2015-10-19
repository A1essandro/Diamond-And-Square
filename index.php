<?php

require 'src/DiamondAndSquare.php';

$preSize = 3;
$maxOffset = 100;

$gen = new MapGenerator\DiamondAndSquare();
$gen->generate($preSize, $maxOffset)->getMap(); //return float[][]

//OR

MapGenerator\DiamondAndSquare::generateAndGetMap($preSize, $maxOffset); //return float[][]
