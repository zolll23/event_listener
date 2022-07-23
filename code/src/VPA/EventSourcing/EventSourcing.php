<?php


namespace VPA\EventSourcing;


use ReflectionClass;
use RuntimeException;

class EventSourcing
{
    static private array $events = [];
    static private array $commands = [];
    static private array $commandHandlers = [];

    static public function registerEvents()
    {
        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, 'VPA\EventSourcing\Entity')) {
                $reflectionClass = new ReflectionClass($class);
                $attributes = $reflectionClass->getAttributes();
                foreach ($attributes as $attribute) {
                    $typeOfEntity = $attribute->getName();
                    $arguments = $attribute->getArguments();
                    if (empty($arguments)) {
                        throw new RuntimeException('Routing key for Entity not set');
                    }
                    $routingKey = reset($arguments);
                    switch ($typeOfEntity) {
                        case 'VPA\EventSourcing\Event':
                            self::$events[$class] = $routingKey;
                            break;
                        case 'VPA\EventSourcing\Command':
                            self::$commands[$class] = $routingKey;
                            break;
                    }
                }
            }
        }
    }

    static public function registerCommandHandler($listenMethod, $run)
    {
        self::$commandHandlers[$listenMethod][] = $run;
    }

    static public function say(string $className, mixed $data)
    {
        if (!array_key_exists($className,self::$commands)) {
            throw new RuntimeException("Command for entity ${className} not found");
        }
        if (!array_key_exists($className,self::$commandHandlers)) {
            return false;
        }
        $command = new $className;
        foreach ($data as $key => $value) {
            $command->$key = $value;
        }
        foreach (self::$commandHandlers[$className] as $handler) {
            assert(is_array($handler));
            call_user_func(array($handler['classname'], $handler['method']), $command);
        }
    }
}