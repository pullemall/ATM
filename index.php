<?php
namespace App;

use App\Core\AnnotationParser;
use App\Core\Schema;
use App\Views\UserLoginView;
use App\Views\UserRegisterView;

include "autoload.php";

$option = $argv[1] ?? null;

$optionsList = array("Show balance", "Transfer money", "Withdraw money", "Deposit money");
$objectsList = array(
    "Show balance" => "UserShowBalanceView",
    "Transfer money" => "UserTransferView",
    "Withdraw money" => "UserWithdrawView",
    "Deposit money" => "UserDepositView",
);

if($option === "migrate") {
    $files = scandir(getcwd() . "/models");
    $files = preg_grep("/[A-z]+\.php/", $files);

    array_walk($files, function($value, $key) {
        $class_name = explode(".", $value)[0];

        $m = new Schema(mb_strtolower($class_name));
        $ref = new AnnotationParser("App\\Models\\" . $class_name);

        foreach($ref->getAnnotationFields() as $key => $val) {
            $m->add_field($key, $val);
        }

        $m->migrate();
    });
    die("Database was created.");
}

if($option == "register") {
    new UserRegisterView();
}

while(true) {
    $userLogIn = new UserLoginView();
    $user = $userLogIn->loginUser();

    if($user) {
        while(true)
        {
            print("What do you want to do?\n\n");
            foreach($optionsList as $key => $option) {
                print("[$key] " . $option . "\n");
            }
            print("\n[9] Exit\n");
            $user_input = readline("\ninput: ");

            if(array_key_exists($user_input, $optionsList)) {
                $view_name = "App\\Views\\" . $objectsList[$optionsList[$user_input]];
                new $view_name($user);
            }
            else if($user_input == 9) {
                die("\nBye!\n");
            } else {
                print("\n\nWrong input.\n");
            }
        }
    }
}