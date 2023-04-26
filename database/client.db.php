<?php

declare(strict_types=1);

class Client
{

    public string $username;

    public string $email;

    public string $displayName;

    public string $password;


    public function __construct(string $_username, string $_email,
        string $_displayName, string $_password
    ) {
        $this->username    = $_username;
        $this->email       = $_email;
        $this->displayName = $_displayName;
        $this->password    = $_password;

    }


}
