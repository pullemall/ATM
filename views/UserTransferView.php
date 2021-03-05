<?php
namespace App\Views;

use App\Controllers\UserController;

class UserTransferView
{
    public function __construct($user)
    {
        $userController = new UserController();

        $recipient_id = readline("\nPlease eneter recipient card id: ");
        $amount = readline("\nEnter transfer amount: ");
        $result = $userController->transferMoney($user, $amount, $recipient_id);

        if(is_numeric($result))
            print("\nTransfer was successful.\n");
        else
            print("\nRecipient card id is wrong or you do not have enough money.\n");
        
    }
}