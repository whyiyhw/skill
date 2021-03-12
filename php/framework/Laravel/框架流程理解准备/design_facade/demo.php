<?php

/**
 * Class CacheFacade
 *
 * 门面或者叫外观模式 就是对 某一行为的 规划与封装 感觉跟接口的概念比较相近
 *
 * 在外观模式中，外部与一个子系统的通信必须通过一个统一的外观对象进行，为子系统中的一组接口提供一个一致的界面，
 * 外观模式定义了一个高层接口，这个接口使得这一子系统更加容易使用。外观模式又称为门面模式，它是一种对象结构型模式
 *
 * 外观模式主要优点在于对客户屏蔽子系统组件，减少了客户处理的对象数目并使得子系统使用起来更加容易，
 * 它实现了子系统与客户之间的松耦合关系，并降低了大型软件系统中的编译依赖性，简化了系统在不同平台之间的移植过程；
 *
 * 缺点在于不能很好地限制客户使用子系统类，
 * 而且在不引入抽象外观类的情况下，增加新的子系统可能需要修改外观类或客户端的源代码，违背了 “开闭原则”。
 *
 * 外观模式要求一个子系统的外部与其内部的通信通过一个统一的外观对象进行，外观类将客户端与子系统的内部复杂性分隔开，
 * 使得客户端只需要与外观对象打交道，而不需要与子系统内部的很多对象打交道。
 *
 * 调用者无需关注具体子系统是如何实现的，实现类被要求去补充门面提出的所有方法
 *
 */
class CacheFacade
{
    // 定义门面与对应子系统关联的配置
    public $config = [
        "redis" => CacheRedis::class,
        "local" => CacheLocalFile::class
    ];
    // 存储对象 单例 map
    public $inst = [];

    /**
     * @param string $electCacheName
     * @return mixed
     */
    public function adapt(string $electCacheName)
    {
        if (array_key_exists($electCacheName, $this->config)) {
            if (!isset($this->inst[$electCacheName])) {
                $this->inst[$electCacheName] = make($this->config[$electCacheName]);
            }
            return $this->inst[$electCacheName];
        }

        throw new LogicException("暂不支持该 缓存方式~");
    }
}

interface CacheInterface
{
    public function get($name);

    public function set($name);
}

class CacheRedis implements CacheInterface
{
    public function get($name)
    {
        echo "this CacheRedis get:" . $name;
    }

    public function set($name)
    {
        echo "this CacheRedis set:" . $name;
    }
}

class CacheLocalFile implements CacheInterface
{
    public function get($name)
    {
        echo "this CacheLocalFile get:" . $name;
    }

    public function set($name)
    {
        echo "this CacheLocalFile set:" . $name;
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
    if (is_null($constructor)){
        return $ReflectClass->newInstanceArgs();
    }
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

$obj = (new CacheFacade());

$obj->adapt("redis")->get("testName");

$obj->adapt("local")->set("testName");