<?php
namespace App\Controllers;

use App\Models\User as UserModel;
use App\Models\Bank;

class UserController
{
    /**
     * Registers user.
     * 
     * Creates an instance of User and Bank models, collects the data and 
     * saves the user and account into database.
     * 
     * @param string $username
     * @param string $password
     * 
     * @return UserModel|false User model instance or false
     */
    public function registerUser(string $username, string $password)
    {
        $user = new UserModel();
        $bank = new Bank();

        $user->setName($username);
        $user->setPassword($password);
        $bank->setBankAccountNumber();
        $card_id = $bank->get_field("bank_account_number");
        $user->set_field("card_id", $card_id);

        if($bank->save() && $user->save()) {
            return $user;
        }

        return false;
    }

    /**
     * Returns the users current balance
     * 
     * @param UserModel $user An instance of user model
     */
    public function showBalance(UserModel $user)
    {
        $bank = new Bank();
        return $bank->getBalance($user);
    }

    /**
     * Withdraws money.
     * 
     * @param UserModel $user An instance of user model
     * @param string $amount Amount to withdraw
     * 
     * @return Bank|false
     */
    public function withdrawMoney(UserModel $user, string $amount)
    {
        $bank = new Bank();

        if($amount >= 0)
            return $bank->changeBalance($user->get_field("card_id"), $amount, "-");
        else
            return false;
    }

    /**
     * Deposits money
     * 
     * @param Usermodel $user An instance of user model
     * @param string $amount Amount to deposit
     * 
     * @return Bank|false
     */
    public function depositMoney(UserModel $user, string $amount)
    {
        $bank = new Bank();

        if($amount >= 0)
            return $bank->changeBalance($user->get_field("card_id"), $amount, "+");
        else
            return false;
    }

    /**
     * Transfer money to another user
     * 
     * Checks if the recipient exists, the current user have enough money to transfer
     * and the user to transfer isn't himself.
     * 
     * @param UserModel $user An instance of user model
     * @param string $amount Amount to transfer
     * @param string $recipient_id The recipient id
     * 
     * @return int|false new balance
     */
    public function transferMoney(UserModel $user, string $amount, string $recipient_id)
    {
        $bank = new Bank();

        $recipient = $bank->checkBankAccount($recipient_id);
        $current_balance = $bank->getBalance($user);

        if($recipient && ($current_balance - $amount) >= 0 && $recipient_id != $user->get_field("card_id")) {
            $new_balance = $bank->changeBalance($user->get_field("card_id"), $amount, "-");
            $bank->changeBalance($recipient["bank_account_number"], $amount, "+");

            return $new_balance;
        }

        return false;
    }

    /**
     * Controller for the user login
     * 
     * @param string $card_id Card id that identifies the account in the bank
     * @param string $password Users password
     * 
     * @return UserModel $user
     */
    public function loginUser(string $card_id, string $password)
    {
        $user = new UserModel();
        $user = $user->getUserAccount($card_id, $password);

        return $user;
    }
}