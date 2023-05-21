<?php

declare(strict_types = 1);

class Department implements JsonSerializable
{

    public string $name;

    public string $description;

    public array $clients = [];

    public int $count = 0;


    public function jsonSerialize(): mixed
    {
        return [
            "name"        => $this->name,
            "description" => $this->description,
            "clients"     => $this->clients,
        ];

    }


    public function __construct(string $_name, ?string $_description="")
    {
        $this->name        = $_name;
        $this->description = $_description;

    }


}


function get_departments(?int $limit, int $offset, PDO $db, $returnClients=true) : array
{
    $sql = "SELECT * FROM Departments";
    if ($limit !== null) {
        $sql .= " LIMIT :limit OFFSET :offset";
    }

    $stmt = $db->prepare($sql);

    if ($limit !== null) {
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
    }

    $stmt->execute();

    $departmentsData = $stmt->fetchAll();

    if ($returnClients === false) {
        return array_map(
            function (array $a) use ($db) {
                $department = new Department($a["name"], $a["description"]);

                $sql  = "SELECT COUNT(*) FROM AgentDepartments WHERE department=:department";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":department", $a["name"], PDO::PARAM_INT);
                $stmt->execute();
                $department->count = $stmt->fetchColumn();
                return $department;
            },
            $departmentsData
        );
    } else {
        return array_map(
            function (array $a) use ($db) {
                $department = new Department($a["name"], $a["description"]);

                $sql  = "SELECT * FROM AgentDepartments ad WHERE department=:department";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":department", $a["name"], PDO::PARAM_INT);
                $stmt->execute();
                $userData = $stmt->fetchAll();

                $department->clients = array_map(
                    function (array $a) use ($db) {
                        return get_user($a["agent"], $db);
                    },
                    $userData
                );
                $department->count   = count($department->clients);
                return $department;
            },
            $departmentsData
        );
    }

}


function get_department(string $name, PDO $db) : ?Department
{

    $sql = "SELECT * FROM Departments WHERE name=:name";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(":name", $name);

    $stmt->execute();

    $a = $stmt->fetch();
    if ($a === false) {
        return null;
    }

    $department = new Department($a["name"], $a["description"]);

    $sql  = "SELECT * FROM AgentDepartments ad WHERE department=:department";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":department", $a["name"], PDO::PARAM_INT);
    $stmt->execute();
    $userData = $stmt->fetchAll();

    $department->clients = array_map(
        function (array $a) use ($db) {
            return get_user($a["agent"], $db);
        },
        $userData
    );
    $department->count   = count($department->clients);
    return $department;

}


function add_department(string $name, string $description, array $members, PDO $db)
{

    $sql = "INSERT INTO Departments VALUES (:namee, :description)";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(":namee", $name);
    $stmt->bindParam(":description", $description);

    if ($stmt->execute() === false) {
        log_to_stdout("Failed to create department ".$name, "e");
        return;
    }

    if (count($members) === 0) {
        return;
    }

    foreach ($members as $member) {
        $sql  = "INSERT INTO AgentDepartments VALUES (:department, :member)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":department", $name);
        $stmt->bindParam(":member", $member);
        if ($stmt->execute() === false) {
            log_to_stdout("Failed to assign ".$member." to department ".$name, "e");
            continue;
        }
    }

}


function edit_department(string $name, string $description, array $members, PDO $db)
{
    //TODO: make this when the department can add members

}


function delete_department(string $name, PDO $db) : bool
{
    $sql = "DELETE FROM AgentDepartments WHERE department=:department";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(":department", $name);

    if ($stmt->execute() === false) {
        log_to_stdout("Failed to remove agents from department ".$name, "e");
        return false;
    }

    //TODO: make this delete cascade to a "deleted department"

    $sql  = "DELETE FROM Departments WHERE name=:name";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":name", $name);

    return $stmt->execute();

}
