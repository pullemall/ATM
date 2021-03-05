# ATM
Test task
The ATM created with the next functionality:
1. System can register a new user.
2. User can log in to the system.
3. User can choose one of four options.
4. User can withdraw or deposit money.
5. User can transfer money to another user.
6. User can check the balance.

Before start you must create the database with command:
```
php index.php migrate
```
Then you can register a new user:
```
php index.php register
```
If the two previous operations are successful you'll get a user's card id. Then run:
```
php index.php
```
And you'll see a request to log in with data that was mentioned above.
After that you will see the menu with options for withdrawing, deposit, check balance and transfer money.
