<?php

declare(strict_types=1);

//  todo: make this an env variable
$salt = 'alskdjfhg';


function hash_text(string $text) : string
{
    global $salt;
    return password_hash($salt.$text.$salt, PASSWORD_DEFAULT);

}


function verify_hash(string $text, string $hash) : bool
{
    global $salt;
    return password_verify($salt.$text.$salt, $hash);

}
