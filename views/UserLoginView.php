<?php

namespace App\Views;

use App\Controllers\UserController;

class UserLoginView
{
    protected $card_id;
    protected $password;

    public function __construct()
    {
        print("Please log in.\n");
        $this->card_id = readline("Enter your card id: ");
        $this->password = readline("Enter your password: ");
    }

    public function loginUser()
    {
        $userController = new UserController();
        $user = null;

        if(mb_strlen($this->card_id) >= 5 && mb_strlen($this->password) != 0)
            $user = $userController->loginUser($this->card_id, $this->password);

        if($user) {
            print("\nHello " . $user->get_field("name") . "\n");
            return $user;
        }
        
        print("\nWrong password.\n\n");

        return false;
    }
}