# Diamond And Square (PHP)

[![Build Status](https://travis-ci.org/A1essandro/Diamond-And-Square.svg)](https://travis-ci.org/A1essandro/Diamond-And-Square) [![Coverage Status](https://coveralls.io/repos/A1essandro/Diamond-And-Square/badge.svg?branch=master&service=github)](https://coveralls.io/github/A1essandro/Diamond-And-Square?branch=master)

Algorithm for generating heightmaps on PHP.

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
$gen->setMapHash("1hCaHs5hZ");

$map = $gen->generate();
```
