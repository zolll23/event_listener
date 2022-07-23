<?php


namespace VPA\EventSourcing;

#[\Attribute]
abstract class Event
{
    function __construct(protected string $eventName)
    {
    }
}