<?php


namespace VPA\EventSourcing;

#[\Attribute]
class CommandHandler
{
    function __construct()
    {
        echo "CommandHandler";
    }
}