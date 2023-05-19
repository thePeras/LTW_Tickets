<?php

declare(strict_types=1);

class Ticket
{

    public int $id;

    public string $title;

    public string $description;

    public string $status;

    public string $hashtags;

    public string $assignee;

    public string $createdByUser;

    public string $department;

    public Datetime $createdAt;


    public function __construct(string $title, string $description, int $_epoch, $id=0, string $status="",
        string $hashtags="", string $assignee="", string $createdByUser="", string $department="",
    ) {
        $this->id            = $id;
        $this->title         = $title;
        $this->description   = $description;
        $this->status        = $status;
        $this->hashtags      = $hashtags;
        $this->assignee      = $assignee;
        $this->createdByUser = $createdByUser;
        $this->department    = $department;
        $this->createdAt     = new DateTime("@".$_epoch);

    }


}


function insert_new_ticket(Session $session, Ticket $ticket, PDO $db) : int
{
    $sql = "INSERT INTO Tickets(title, description, createdByUser, createdAt) VALUES (:title, :description, :createByUser, :createdAt)";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':createByUser', $session->username, PDO::PARAM_STR);
    $stmt->bindParam(':title', $ticket->title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $ticket->description, PDO::PARAM_STR);
    $stmt->bindParam(':createdAt', $ticket->createdAt->getTimestamp(), PDO::PARAM_INT);

    if ($stmt->execute() === false) {
        return 0;
    }

    return (int) $db->lastInsertId();

}


function get_ticket(int $id, PDO $db) : ?Ticket
{
    $sql  = "SELECT * FROM Tickets WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch();
    if ($result === false) {
        return null;
    }

    return new Ticket(
        $result['title'],
        $result['description'],
        (int) $result['createdAt'],
        (int) $result['id'],
        ($result['status'] ?? ""),
        ($result['hashtags'] ?? ""),
        ($result['assignee'] ?? ""),
        ($result['createdByUser'] ?? ""),
        ($result['department'] ?? ""),
    );

}


function change_ticket_department(int $id, string $department, PDO $db) : bool
{
    $sql  = "UPDATE Tickets SET department = :department WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':department', $department, PDO::PARAM_STR);
    return $stmt->execute();

}


function update_ticket_department(Ticket $ticket, PDO $db) : bool
{
    $sql  = "UPDATE Tickets SET department = :department WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $ticket->id, PDO::PARAM_INT);
    $stmt->bindParam(':department', $ticket->department, PDO::PARAM_STR);
    return $stmt->execute();

}


function remove_ticket_department(Ticket $ticket, PDO $db) : bool
{
    $sql  = "UPDATE Tickets SET department = NULL WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $ticket->id, PDO::PARAM_INT);
    return $stmt->execute();

}


function update_ticket_status(Ticket $ticket, PDO $db) : bool
{
    $sql  = "UPDATE Tickets SET status = :status WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $ticket->id, PDO::PARAM_INT);
    $stmt->bindParam(':status', $ticket->status, PDO::PARAM_STR);
    return $stmt->execute();

}
