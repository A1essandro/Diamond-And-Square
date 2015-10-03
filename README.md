# Diamond And Square (PHP)

Algorithm for generating heightmaps on PHP.
See more about diamond-square algorithm on [wiki](https://en.wikipedia.org/wiki/Diamond-square_algorithm)


##Requirements

This package is only supported on PHP 5.3 and up.


##Installing

###Installing via Composer

See more [getcomposer.org](http://getcomposer.org). 
```
    //before
    "require": {
        //other requirements
        "a1essandro/diamond-and-square": "dev-master"
    },
    //after
```

 
##Usage example

###Standart

```php
$preSize = 3; //generates 9x9  map (2^3 + 1), e.g. preSize=1 generates map 3x3
$maxOffset = 100; // -50 <= height <= 50
$gen = new MapGenerator\DiamondAndSquare($preSize, $maxOffset);
$heightMap = $gen->generate()->getMap();
```

###Alternative

```php
MapGenerator\DiamondAndSquare::generateAndGetMap($preSize, $maxOffset); //return float[][]
```