# Diamond And Square
Algorithm for generating heightmaps on PHP

##Usage example

###Standart:

```php
$preSize = 3; //generates 9x9  map (2^3 + 1), e.g. preSize=1 generates map 3x3
$maxOffset = 100; // -50 <= height <= 50
$gen = new MapGenerator\DiamondAndSquare($preSize, $maxOffset);
$heightMap = $gen->generate()->getMap();
```

###Alternative:

```php
MapGenerator\DiamondAndSquare::generateAndGetMap($preSize, $maxOffset); //return float[][]
```