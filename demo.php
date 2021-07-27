<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

use VPA\Eventer;
require_once('VPA/Eventer.php');
echo "Events lib\n\n";

Eventer::subscribe('Numerator_generate', [Operator::class, 'sum']);
Eventer::subscribe('Digits_generate', [Operator::class, 'sub']);

$n = new Numerator();
$n->generate();

class Digits
{
}

class Numerator extends Digits
{
    function generate()
    {
        $a = rand(10, 99);
        $b = rand(100, 200);
        printf("A=%d B=%d\n", $a, $b);
        Eventer::send('generate', $this, array($a, $b));
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

    static function sub($data)
    {
        $a = $data[0];
        $b = $data[1];
        echo "Sub=" . ($a - $b);
        echo "\n";
    }
}


