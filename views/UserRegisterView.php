<?php
namespace App\Views;

use App\Controllers\UserController;

class UserRegisterView
{
    protected $username;
    protected $password;

    public function __construct()
    {
        $userController = new UserController;

        $this->username = readline("Please enter your name: ");
        $this->password = readline("Enter your password: ");
        
        $user = $userController->registerUser($this->username, $this->password);

        if($user) {
            print("Registration was successful.\n");
            print("Your card id is: " . $user->get_field("card_id"));
            print("\nYour balance is: " . $user->getBalance() . "\n");
        }
    }
}