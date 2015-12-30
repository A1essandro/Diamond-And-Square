# Diamond And Square (PHP)

[![Build Status](https://travis-ci.org/A1essandro/Diamond-And-Square.svg)](https://travis-ci.org/A1essandro/Diamond-And-Square) [![Coverage Status](https://coveralls.io/repos/A1essandro/Diamond-And-Square/badge.svg?branch=master&service=github)](https://coveralls.io/github/A1essandro/Diamond-And-Square?branch=master) [![Latest Stable Version](https://poser.pugx.org/a1essandro/diamond-and-square/v/stable)](https://packagist.org/packages/a1essandro/diamond-and-square) [![Total Downloads](https://poser.pugx.org/a1essandro/diamond-and-square/downloads)](https://packagist.org/packages/a1essandro/diamond-and-square) [![Latest Unstable Version](https://poser.pugx.org/a1essandro/diamond-and-square/v/unstable)](https://packagist.org/packages/a1essandro/diamond-and-square) [![License](https://poser.pugx.org/a1essandro/diamond-and-square/license)](https://packagist.org/packages/a1essandro/diamond-and-square)

Algorithm for generating heightmaps on PHP.

See also [Perlin-Noise algorithm](https://github.com/A1essandro/perlin-noise-generator) with the similar API.

##Algorithm
###Steps
![Steps](http://www.cs.middlebury.edu/~candrews/classes/cs461/programs/images/program6_diamond_square.png)

_[Image source](http://www.cs.middlebury.edu/~candrews/classes/cs461/programs/program6.html)_

See more about diamond-square algorithm on [wiki](https://en.wikipedia.org/wiki/Diamond-square_algorithm)


##Requirements

This package is only supported on PHP 5.3 and up.


##Installing

###Installing via Composer

See more [getcomposer.org](http://getcomposer.org).

Execute command 
```
composer require a1essandro/diamond-and-square ~2.0
```

 
##Usage example

###Standart

```php
$gen = new DiamondAndSquare();
$gen->setSize(7); //real size equal 2 ^ 7 + 1, i.e. 129
$gen->setPersistence(200);
$gen->setMapSeed("1hCaHs5hZ"); //optional

$map = $gen->generate();
```
