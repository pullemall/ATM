<?php
namespace App\Models;

use App\Core\BaseModel;

class User extends BaseModel
{
    protected $schema;
    
    /** @var STRING */
    protected $name;

    /** @var INTEGER UNIQUE */
    protected $card_id;
    
    /** @var STRING, FOREIGN KEY(card_id) REFERENCES bank(bank_account_number) */
    protected $password;

    function __construct()
    {
        parent::__construct();
    }

    public function getBalance()
    {
        $bank = new Bank();
        return $bank->getBalance($this);
    }

    public function setName($name)
    {
        if(is_string($name))
            $this->set_field("name", $name);
        else
            return false;
    }

    public function setPassword($password)
    {
        if(is_string($password))
            $this->set_field("password", $password);
        else
            return false;
    }

    public function getUserAccount($card_id, $password)
    {
        $bank = new Bank();
        $query = "SELECT name, card_id, password, balance FROM " . $this . " ";
        $query .= "JOIN " . $bank . " ON " . $bank . ".bank_account_number = " . $this . ".card_id";
        $query .= " WHERE card_id = " . $card_id . " AND password = '" . $password . "'";
        $result = $this->query($query);
        $result = $result->fetchArray(SQLITE3_ASSOC);

        if(!$result)
            return false;

        foreach($result as $key => $val) {
            $this->set_field($key, $val);
        }

        return $this;
    }

}