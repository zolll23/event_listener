<?php

namespace Tests;

use VPA\Eventer;
use PHPUnit\Framework\TestCase;

class Digits
{
}

class Numerator extends Digits
{
    function generate()
    {
        $a = 100;
        $b = 50;
        printf("A=%d B=%d\n", $a, $b);
        Eventer::send('generate', $this, [$a, $b]);
    }
}

class Operator
{
    static function sum($data, $event_name)
    {
        $a = $data[0];
        $b = $data[1];
        echo "Sum ($event_name)=" . ($a + $b);
        echo "\n";
    }

    static function sub($data, $event_name)
    {
        $a = $data[0];
        $b = $data[1];
        echo "Sub ($event_name)=" . ($a - $b);
        echo "\n";
    }
}


class EventerTest extends TestCase
{
    public function testSubscribeEvent()
    {
        Eventer::subscribe(Numerator::class.'::generate', [Operator::class, 'sum']);
        Eventer::subscribe(Digits::class.'::generate', [Operator::class, 'sub']);
        $this->assertTrue(!empty(Eventer::getSubscribes()));
    }

    public function testListenCurrentClassEvent()
    {
        $Numerator = new Numerator;
        ob_start();
        $Numerator->generate();
        $str = ob_get_clean();
        $this->assertTrue(strpos($str,Numerator::class)!==false);
    }

    public function testListenParentClassEvent()
    {
        $Numerator = new Numerator;
        ob_start();
        $Numerator->generate();
        $str = ob_get_clean();
        $this->assertTrue(strpos($str,Digits::class)!==false);
    }

}