<?php

use VPA\EventSourcing\CommandHandler;


class UserUseCase extends VPA\EventSourcing\Listener
{

    #[CommandHandler]
    public static function saveUser(UserCreate $command)
    {
        echo "saveUser(): \n";
        var_dump($command);
        echo "\n----------------\n";
    }

    #[CommandHandler]
    public static function referralUser(UserCreate $command)
    {
        echo "referralUser(): \n";
        var_dump($command);
        echo "\n----------------\n";
    }
}