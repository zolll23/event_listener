<?php

namespace VPA;

class Eventer
{
    static private array $events = [];

    static function make_event(string $event_name, $generator, $data)
    {
        if (isset(self::$events[$event_name])) {
            foreach (self::$events[$event_name] as $method) {
                call_user_func(array($method['classname'], $method['method']), $data, $event_name, $generator);
            }
        }
    }

    static function send(string $event, $generator = null, $data = null)
    {
        if (!$data) $data = $generator;

        self::make_event($event, $generator, $data);

        if (!is_object($generator)) return;

        $classes = class_parents($generator);
        $classes[] = get_class($generator);
        foreach ($classes as $class) {
            $event_name = $class . '_' . $event;
            self::make_event($event_name, $generator, $data);
        }
    }

    static public function subscribe(string $event_name, array $method): void
    {
        $key = implode("::", $method);
        self::$events[$event_name][$key] = [
            'classname' => $method[0],
            'method' => $method[1]
        ];
    }

    static public function unsubscribe(string $event_name, array $method)
    {
        $key = implode("::", $method);
        unset(self::$events[$event_name][$key]);
    }

    static function clean_subscribes()
    {
        self::$events = [];
    }
}

