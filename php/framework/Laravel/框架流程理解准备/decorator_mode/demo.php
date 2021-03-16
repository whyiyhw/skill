<?php

class Decorator
{
    public $beforeMethod = [];
    public $afterMethod = [];

    public function doSomething()
    {
        $this->beforeDoSomething();

        echo "doing something" . PHP_EOL;

        $this->afterDoSomething();
    }

    public function beforeDoSomething()
    {
        foreach ($this->beforeMethod as $v) {
            $v->exec();
        }
    }

    public function afterDoSomething()
    {
        foreach ($this->afterMethod as $v) {
            $v->exec();
        }
    }

    public function registerBeforeEvent(Event $event)
    {
        $this->beforeMethod[] = $event;
    }

    public function registerAfterEvent(Event $event)
    {
        $this->afterMethod[] = $event;
    }
}

interface Event
{
    public function exec();
}

class BeforeDoSomethingEvent implements Event
{
    public function exec()
    {
        echo "before doSomething" . PHP_EOL;
    }
}

class BeforeEvent implements Event
{
    public function exec()
    {
        echo "before doSomething other" . PHP_EOL;
    }
}

class afterDoSomethingEvent implements Event
{
    public function exec()
    {
        echo "after doSomething" . PHP_EOL;
    }
}

$obj = new Decorator();

$beforeEvent  = new beforeDoSomethingEvent();
$beforeEvent2 = new BeforeEvent();
$afterEvent   = new afterDoSomethingEvent();
$obj->registerBeforeEvent($beforeEvent);
$obj->registerBeforeEvent($beforeEvent2);
$obj->registerAfterEvent($afterEvent);

$obj->doSomething();

