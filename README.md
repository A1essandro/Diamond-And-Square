# Diamond And Square (PHP)

Algorithm for generating heightmaps on PHP.

See more about diamond-square algorithm on [wiki](https://en.wikipedia.org/wiki/Diamond-square_algorithm)


##Requirements

This package is only supported on PHP 5.3 and up.


##Installing

###Installing via Composer

See more [getcomposer.org](http://getcomposer.org).

Execute command 
```
composer require a1essandro/diamond-and-square ~1.0
```

 
##Usage example

###Standart

```php
$preSize = 3; //generates 9x9  map (2^3 + 1), where 3 is preSize, e.g. preSize=1 generates map 3x3
$maxOffset = 100; // -50 <= height <= 50
$gen = new MapGenerator\DiamondAndSquare();
$gen->generate($preSize, $maxOffset)->getMap();
```

###Alternative

```php
MapGenerator\DiamondAndSquare::generateAndGetMap($preSize, $maxOffset);
```
