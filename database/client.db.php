<?php

declare(strict_types=1);

require_once __DIR__."/../utils/hash.php";

class Client
{

    public string $username;

    public string $email;

    public string $displayName;

    public string $password;


    public function __construct(string $_username, string $_email,
        string $_password, string $_displayName
    ) {
        $this->username    = $_username;
        $this->email       = $_email;
        $this->password    = $_password;
        $this->displayName = $_displayName;

    }


}


function insert_new_client(Client $client, PDO $db) : bool
{
    $sql = "INSERT INTO Clients VALUES (:username, :email, :password, :display_name)";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $client->username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $client->email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $client->password, PDO::PARAM_STR);
    $stmt->bindParam(':display_name', $client->displayName, PDO::PARAM_STR);

    return $stmt->execute();

}


function user_already_exists(string $username, PDO $db) : bool
{
    $sql  = "SELECT * FROM Clients WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch() !== false;

}


function get_user(string $username, PDO $db) : ?Client
{
    $sql  = "SELECT * FROM Clients WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row === false) {
        return null;
    }

    return new Client($row['username'], $row['email'], $row['password'], $row['displayName']);

}


function change_password(string $username, string $newPassword, PDO $db) : bool
{
    $hashedPassword = hash_text($newPassword);
    $sql            = "UPDATE Clients SET password=:password, passwordInvalidated=0 WHERE username=:username";
    $stmt           = $db->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

    return $stmt->execute();

}


function is_user_password_invalidated(string $username, PDO $db) : bool
{
    $sql  = "SELECT passwordInvalidated FROM Clients WHERE username=:username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    $stmt->execute();

    return $stmt->fetchColumn() === 1;

}
