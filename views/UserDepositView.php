<?php
namespace App\Views;

use App\Controllers\UserController;

class UserDepositView
{
    public function __construct($user)
    {
        $amount = readline("\nPlease enter deposit amount: ");
        $userController = new UserController();
        
        $new_balance = $userController->depositMoney($user, $amount);

        if(is_numeric($new_balance))
            print("Successful deposit!\n\nYour balance is: " . $new_balance . "\n\n");
    }
}