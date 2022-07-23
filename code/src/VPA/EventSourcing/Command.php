<?php


namespace VPA\EventSourcing;

#[\Attribute]
abstract class Command
{
    function __construct(protected string $eventName)
    {
    }
}