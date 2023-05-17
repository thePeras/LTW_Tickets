<?php

declare(strict_types=1);

class Client
{

    const DEFAULT_IMAGE = 'assets/images/default_user.png';

    public string $username;

    public string $displayName;

    public string $email;

    public string $password;

    public string $image;


    public function __construct(string $_username, string $_displayName, string $_email,
        string $_password, string $_image=null
    ) {
        $this->username    = $_username;
        $this->displayName = $_displayName;
        $this->email       = $_email;
        $this->password    = $_password;
        $this->image       = ($_image ?? self::DEFAULT_IMAGE);

    }


}


function insert_new_client(Client $client, PDO $db) : bool
{
    $sql = "INSERT INTO Clients VALUES (:username, :email, :password, :display_name, :image)";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $client->username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $client->email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $client->password, PDO::PARAM_STR);
    $stmt->bindParam(':display_name', $client->displayName, PDO::PARAM_STR);
    $stmt->bindParam(':image', $client->image, PDO::PARAM_STR);

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

    return new Client($row['username'], $row['displayName'], $row['email'], $row['password'], $row['image']);

}


function edit_user(Client $oldUser,
    Client $newUser,
    PDO $db
) : bool {

    $sql  = "UPDATE Clients SET username = :new_username, email = :new_email, password = :new_password, displayName = :new_display_name, image = :new_image WHERE username = :old_username";
    $stmt = $db->prepare($sql);

    $newUsername    = $newUser->username;
    $newDisplayName = $newUser->displayName;
    $newEmail       = $newUser->email;
    $newPassword    = $newUser->password;
    $newImage       = $newUser->image;

    $stmt->bindParam(':new_username', $newUsername, PDO::PARAM_STR);
    $stmt->bindParam(':new_email', $newEmail, PDO::PARAM_STR);
    $stmt->bindParam(':new_password', $newPassword, PDO::PARAM_STR);
    $stmt->bindParam(':new_display_name', $newDisplayName, PDO::PARAM_STR);
    $stmt->bindParam(':new_image', $newImage, PDO::PARAM_STR);
    $stmt->bindParam(':old_username', $oldUser->username, PDO::PARAM_STR);

    return $stmt->execute();

}
