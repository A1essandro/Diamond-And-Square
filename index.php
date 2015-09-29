<?php

require 'src/DiamondAndSquare.php';

$gen = new MapGenerator\DiamondAndSquare(3, 100);
$gen->generate();
$gen->getMap(); //return float[][]

//OR

MapGenerator\DiamondAndSquare::generateAndGetMap(3, 100); //return float[][]
