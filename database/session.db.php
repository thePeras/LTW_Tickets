<?php

declare(strict_types=1);

class Session
{

    public string $username;

    public string $token;

    public DateTime $lastUsed;


    public function __construct(string $_username, string $_token, int $_epoch)
    {
        $this->username = $_username;
        $this->token    = $_token;
        $this->lastUsed = new DateTime("@".$_epoch);

    }


}
