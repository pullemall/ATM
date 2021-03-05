<?php

namespace App\Models;

use App\Core\BaseModel;

class Bank extends BaseModel
{
    /** @var INTEGER UNIQUE */
    protected $bank_account_number;

    /** @var INTEGER */
    protected $balance;
    
    public function __construct()
    {
        parent::__construct();
        $this->set_field("balance", 1000);
    }

    /**
     * Iteratively saves account numbers
     */
    public function setBankAccountNumber()
    {
        $query = "SELECT bank_account_number FROM " . $this->schema . " ORDER BY bank_account_number DESC LIMIT 1";
        $result = $this->query($query);
        $result = $result->fetchArray(SQLITE3_ASSOC);

        if(!$result){
            $this->set_field("bank_account_number", 10000);
            return;
        }
        
        $this->set_field("bank_account_number", $result["bank_account_number"] + 1);
    }

    /**
     * Returns users bank account with information about balance
     * 
     * @return array An array with information about users bank account
     */
    public function getUserBankAccount($card_id) 
    {
        $query = "SELECT * FROM " . $this . " JOIN user ON " . $this . ".bank_account_number = user.card_id";
        $query .= " WHERE bank_account_number = " . $card_id;
        $result = $this->query($query);
        
        $userBankAccount = $result->fetchArray(SQLITE3_ASSOC);

        return $userBankAccount;
    }

    /**
     * Changes user balance in the bank
     * 
     * The method has two options '+' and '-' that adds or subtracts money from the user's bank account
     * 
     * @return int|false new balance if the operation was successful
     */
    public function changeBalance($user_id, $amount, $method)
    {
        $userBankAccount = $this->getUserBankAccount($user_id);

        if(!$userBankAccount)
            return false;

        if($method == "-")
            $new_balance = $userBankAccount["balance"] - $amount;
        else if($method == "+")
            $new_balance = $userBankAccount["balance"] + $amount;

        if($new_balance >= 0) {
            $this->set_field("id", $userBankAccount["id"]);
            $this->set_field("balance", $new_balance);
            $this->set_field("bank_account_number", $userBankAccount["bank_account_number"]);
            $this->save();

            return $new_balance;
        }
        else
            return false;
    }

    /**
     * Checks if the bank account with passed card_id exists
     * 
     * @param string|int $card_id Card id
     * 
     * @return array|false An array with account information
     */
    public function checkBankAccount($card_id)
    {
        $query = "SELECT * FROM bank WHERE bank_account_number = " . $card_id;
        $result = $this->query($query);

        if($result)
            return $result->fetchArray(SQLITE3_ASSOC);
        else
            return $result;
    }

    /**
     * Returns the balance from the user's bank account
     * 
     * @return int
     */
    public function getBalance($user)
    {
        $query = "SELECT * FROM " . $this . " JOIN " . $user . " ON " . $this . ".bank_account_number = " . $user . ".card_id";
        $query .= " WHERE bank_account_number = " . $user->get_field("card_id");
        $result = $this->query($query);
        $userBankAccount = $result->fetchArray(SQLITE3_ASSOC);

        return $userBankAccount["balance"];
    }
}