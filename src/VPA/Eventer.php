<?php
declare(strict_types=1);

namespace VPA;

/**
 * Class Eventer
 * @package VPA
 */
class Eventer
{
    /** @psalm-var array<array> self::$events */
    static private array $events = [];

    /**
     * Send the event to listeners for current class
     * @param string $event_name
     * @param object $generator
     * @param mixed $data
     */
    static function make_event(string $event_name, object $generator, $data): void
    {
        if (isset(self::$events[$event_name])) {
            foreach (self::$events[$event_name] as $method) {
                assert(is_array($method));
                call_user_func(array($method['classname'], $method['method']), $data, $event_name, $generator);
            }
        }
    }

    /**
     * Send event to all listeners
     * @param string $event
     * @param object $generator
     * @param mixed $data
     */
    static function send(string $event, object $generator, $data = null): void
    {
        self::make_event($event, $generator, $data);

        $classes = class_parents($generator);
        $classes[] = get_class($generator);
        foreach ($classes as $class) {
            $event_name = $class . '::' . $event;
            self::make_event($event_name, $generator, $data);
        }
    }

    /**
     * Add a listener to list of subscribers
     * @param string $event_name
     * @param array $method
     */
    static public function subscribe(string $event_name, array $method): void
    {
        $key = self::getKey($method);
        self::$events[$event_name][$key] = [
            'classname' => $method[0],
            'method' => $method[1]
        ];
    }

    /**
     * Remove a listener from list of subscribers
     * @param string $event_name
     * @param array $method
     */
    static public function unsubscribe(string $event_name, array $method): void
    {
        $key = self::getKey($method);
        unset(self::$events[$event_name][$key]);
    }

    static private function getKey(array $method): string
    {
        return implode("::", $method);
    }

    /**
     * Remove all listeners
     */
    static function cleanSubscribes(): void
    {
        self::$events = [];
    }

    static function getSubscribes(): array
    {
        return self::$events;
    }
}

