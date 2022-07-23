<?php

require_once(__DIR__ . '/../vendor/autoload.php');
require_once (__DIR__ . '/UserUseCase.php');

use VPA\EventSourcing\Event;
use VPA\EventSourcing\Command;
use VPA\EventSourcing\Entity;
use VPA\EventSourcing\EventSourcing;

echo "Events lib\n\n";

$userUseCase = new UserUseCase();

#[Command('user.event.create')]
class UserCreate implements Entity
{
    public string $email;
    public string $password;
}

#[Event('user.event.created')]
class UserCreated implements Entity
{
    public string $id;
}


EventSourcing::registerEvents();

EventSourcing::say(UserCreate::class, ['email'=>'andrey.pahomov@gmail.com', 'password'=>'ajJhahajJy']);

//function executeEvent(Event $event)
//{
//    $reflection = new ReflectionObject($event);
//
//    foreach ($reflection->getName() as $method) {
//        $attributes = $method->getAttributes(SetUp::class);
//
//        if (count($attributes) > 0) {
//            $methodName = $method->getName();
//
//            $actionHandler->$methodName();
//        }
//    }
//
//    $actionHandler->execute();
//}
