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


    public function __construct(string $_name, string $_description)
    {
        $this->name        = $_name;
        $this->description = $_description;

    }


}


function get_departments(int $limit, int $offset, PDO $db, $returnClients=true) : array
{
    $sql  = "SELECT * FROM Departments LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

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
