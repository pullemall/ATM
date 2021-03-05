<?php

namespace App\Views;

use App\Controllers\UserController;

class UserShowBalanceView
{
    public function __construct($user)
    {
        $userController = new UserController();


        print("\n Your current balance is: " . $userController->showBalance($user) . "\n\n");
    }
}