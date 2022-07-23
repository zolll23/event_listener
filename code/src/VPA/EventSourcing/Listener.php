<?php


namespace VPA\EventSourcing;


abstract class Listener
{
    function __construct()
    {
        $reflectionClass = new \ReflectionObject($this);
        foreach ($reflectionClass->getMethods() as $method) {
            $attributes = $method->getAttributes();
            $parameters = $method->getParameters();
            foreach ($attributes as $attribute) {
                $handler = $attribute->getName();
                $arguments = $attribute->getArguments();

                switch ($handler) {
                    case 'VPA\EventSourcing\CommandHandler':
                        if (empty($parameters)) {
                            throw new \RuntimeException("CommandHandler method need a parameter with a type based on the Entity interface");
                        }
                        $listenMethod = $parameters[0]->getType()->getName();
                        printf("%s::%s -> %s(%s)\n", $reflectionClass->getName(), $method->getName(), $handler, $listenMethod);
                        EventSourcing::registerCommandHandler($listenMethod, [
                            'classname' => $reflectionClass->getName(),
                            'method' => $method->getName()
                        ]);
                        break;
                }
            }

        }
    }

}