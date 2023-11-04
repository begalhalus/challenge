<?php
class User
{
    private $username;
    private $password;
    private $balance;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->balance = 0;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getBalance()
    {
        return $this->balance;
    }
}
