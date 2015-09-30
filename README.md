# Diamond And Square
Algorithm for generating heightmaps on PHP

##Exampes

###Standart:

```php
$preSize = 3; //generates 9x9  map (2^3 + 1), e.g. preSize=1 generates map 3x3
$maxOffset = 100; // -50 <= height <= 50
$gen = new MapGenerator\DiamondAndSquare($preSize, $maxOffset);
$gen->generate();
$gen->getMap(); //return float[][]
```

###Alternative:

```php
MapGenerator\DiamondAndSquare::generateAndGetMap($preSize, $maxOffset); //return float[][]
```