<?php

declare(strict_types=1);

require_once __DIR__."/../utils/hash.php";

class Client implements JsonSerializable
{

    public string $username;

    public string $email;

    public string $displayName;

    public string $password;

    public string $type;

    public int $createdAt;


    public function jsonSerialize()
    {
        return [
            "username"    => $this->username,
            "email"       => $this->email,
            "displayName" => $this->displayName,
            "type"        => $this->type,
            "createdAt"   => $this->createdAt,

        ];

    }


    public function __construct(string $_username, string $_email,
        string $_password, string $_displayName, ?int $_createdAt=null
    ) {
        $this->username    = $_username;
        $this->email       = $_email;
        $this->password    = $_password;
        $this->displayName = $_displayName;
        if ($_createdAt === null) {
            $this->createdAt = time();
        } else {
            $this->createdAt = $_createdAt;
        }

    }


}


function insert_new_client(Client $client, PDO $db) : bool
{
    $sql = "INSERT INTO Clients VALUES (:username, :email, :password, :display_name, :createdAt, 0)";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $client->username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $client->email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $client->password, PDO::PARAM_STR);
    $stmt->bindParam(':display_name', $client->displayName, PDO::PARAM_STR);
    $stmt->bindParam(":createdAt", $client->createdAt, PDO::PARAM_INT);

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

    return new Client($row['username'], $row['email'], $row['password'], $row['displayName'], $row["createdAt"]);

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


function get_clients(int $limit, int $offset, PDO $db) : array
{
    $sql = "SELECT * FROM Clients LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

    $stmt->execute();

    $sqlAgent    = "SELECT * FROM Agents";
    $resultAgent = array_map(
        function (array $a) : string {
            return $a[0];
        },
        $db->query($sqlAgent)->fetchAll()
    );

    $sqlAdmin    = "SELECT * FROM Admins";
    $resultAdmin = array_map(
        function (array $a) : string {
            return $a[0];
        },
        $db->query($sqlAdmin)->fetchAll()
    );

    $result = $stmt->fetchAll();

    $clientBuilder = function (array $a) use ($resultAgent, $resultAdmin): ?Client {
        $client       = new Client($a["username"], $a["email"], $a["password"], $a["displayName"], $a["createdAt"]);
        $client->type = "client";
        if (in_array($client->username, $resultAgent) === true) {
            $client->type = "agent";
            if (in_array($client->username, $resultAdmin) === true) {
                $client->type = "admin";
            }
        }

        return $client;
    };

    return array_map($clientBuilder, $result);

}


function get_clients_only(int $limit, int $offset, PDO $db) : array
{
    $sql  = "SELECT * FROM Clients c WHERE username NOT IN (SELECT * FROM Agents) LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

    $stmt->execute();
    $result = $stmt->fetchAll();
    return array_map(
        function (array $a) : Client {
            $client       = new Client($a["username"], $a["email"], $a["password"], $a["displayName"], $a["createdAt"]);
            $client->type = "client";
            return $client;
        },
        $result
    );

}


function get_agents(int $limit, int $offset, PDO $db) : array
{
    $sql  = "SELECT * FROM Clients c JOIN Agents a ON a.username = c.username LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

    $stmt->execute();
    $result = $stmt->fetchAll();
    return array_map(
        function (array $a) : Client {
            $client       = new Client($a["username"], $a["email"], $a["password"], $a["displayName"], $a["createdAt"]);
            $client->type = "agent";
            return $client;
        },
        $result
    );

}


function get_admins(int $limit, int $offset, PDO $db) : array
{
    $sql  = "SELECT * FROM Clients c JOIN Admins a ON a.username = c.username LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

    $stmt->execute();
    $result = $stmt->fetchAll();
    return array_map(
        function (array $a) : Client {
            $client       = new Client($a["username"], $a["email"], $a["password"], $a["displayName"], $a["createdAt"]);
            $client->type = "admin";
            return $client;
        },
        $result
    );

}


function delete_client(string $username, PDO $db) : bool
{
    //TODO: handle all other cases where an client might have an interaction and therefore it
    //might not be succesful when deleting...
    $sql  = "DELETE FROM Sessions WHERE user=:username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":username", $username);

    $stmt->execute();

    $sql  = "DELETE FROM Admins WHERE username=:username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":username", $username);

    $stmt->execute();

    $sql  = "DELETE FROM Agents WHERE username=:username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":username", $username);

    $stmt->execute();

    $sql  = "DELETE FROM Clients WHERE username=:username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":username", $username);

    $stmt->execute();

    return $stmt->rowCount() === 1;

}
