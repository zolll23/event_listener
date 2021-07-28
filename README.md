# Event listener & generator
The subscriber and events generator for multiple listeners.

Events can bubble up through the class inheritance tree, 
which allows listeners to subscribe to parent classes and cover 
a wide range of events.

#Install:

Include eventer to your code:
```
require_once('VPA/Eventer.php');
```

or if you use composer, add next lines:
```
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/zolll23/event_listener",
    }
],
"require": {
    "zolll23/event_listener" : "dev-master"
}
```

#Example of use:

Imagine you have 2 classes:
```
class Digits
{
}
```
and
```
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
```
Last class send event "generate" when calls method "generate".

Сreate a subscription to this event. In first line we listen only events for 
class Numerator, in second line - we listen event for parent class Digits.
```
use VPA\Eventer;
Eventer::subscribe('Numerator::generate', [Operator::class, 'sum']);
Eventer::subscribe('Digits::generate', [Operator::class, 'sub']);
```

Next class include a methods for listeners:
```
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
```

Run next code:
```
$n = new Numerator();
$n->generate();
```

And we can see output:

```
A=50 B=146
Sub (Digits::generate)=-96
Sum (Numerator::generate)=196
```
Call of method "generate"  generated 2 events that were caught and 
served by the corresponding handlers
