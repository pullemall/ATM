<?php

namespace App\Views;

use App\Controllers\UserController;

class UserWithdrawView
{
    public function __construct($user)
    {
        $amount = readline("\nPlease enter withdraw amount: ");

        $userController = new UserController;
        $new_balance = $userController->withdrawMoney($user, $amount);

        if(is_numeric($new_balance))
            print("Successful withdraw!\n\nYour balance is: " . $new_balance . "\n\n");
        else
            print("\nNot enough money to withdraw.\n");
    }
}