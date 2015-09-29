# Diamond And Square
Algorithm for generating heightmaps on PHP

##Exampes

###Standart:

```php
$gen = new MapGenerator\DiamondAndSquare(3, 100);
$gen->generate();
$gen->getMap(); //return float[][]
```

###Alternative:

```php
MapGenerator\DiamondAndSquare::generateAndGetMap(3, 100); //return float[][]
```