<?php

class Circle
{
    /**
     * @var int
     */
    public $radius;

    /**
     * @var Point
     */
    public $center;

    const PI = 3.14;

    public function __construct(Point $point, $radius = 1)
    {
        $this->center = $point;
        $this->radius = $radius;
    }

    public function printCenter()
    {
        printf('center coordinate is (%d, %d)', $this->center->x, $this->center->y);
    }

    public function area()
    {
        return 3.14 * ($this->radius ** 2);
    }
}

/**
 * Class Point
 */
class Point
{
    public $x;
    public $y;

    /**
     * Point constructor.
     * @param int $x horizontal value of point's coordinate
     * @param int $y vertical value of point's coordinate
     */
    public function __construct($x = 0, $y = 0)
    {
        $this->x = $x;
        $this->y = $y;
    }
}


/**
 * make 制造对象的简易版本
 *
 * @param $className
 * @return object
 * @throws ReflectionException
 */
function make($className)
{
    $ReflectClass = new ReflectionClass($className);

    $constructor = $ReflectClass->getConstructor();
    $params      = $constructor->getParameters();

    $constructParas = [];
    foreach ($params as $param) {
        $paraClassName = $param->getClass();
        if (is_null($paraClassName)) {
            $constructParas[] = $param->getDefaultValue() ?? "0";
        } else {
            $constructParas[] = make($param->getClass()->name);
        }
    }

    return $ReflectClass->newInstanceArgs($constructParas);
}

$circle = make(Circle::class);
$area   = $circle->area();

var_dump($area);