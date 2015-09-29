<?php

require 'src/DiamondAndSquare.php';

$gen = new MapGenerator\DiamondAndSquare(3, 100);
$gen->generate();
print_r($gen->getMap());