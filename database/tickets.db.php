<?php

declare(strict_types=1);

class Ticket
{

    public readonly int $id;

    public readonly string $title;

    public readonly string $description;

    public readonly string $status;

    public readonly string $hashtags;

    public readonly string $assignee;

    public readonly string $createdByUser;

    public readonly string $department;


    public function __construct(string $title, string $description, $id=null, string $status="",
        string $hashtags="", string $assignee="", string $createdByUser="", string $department=""
    ) {
        $this->id            = $id;
        $this->title         = $title;
        $this->description   = $description;
        $this->status        = $status;
        $this->hashtags      = $hashtags;
        $this->assignee      = $assignee;
        $this->createdByUser = $createdByUser;
        $this->department    = $department;

    }


}


function insert_new_ticket(Session $session, Ticket $ticket, PDO $db) : bool
{
    $sql = "INSERT INTO Tickets(title, description, createByUser) VALUES (:title, :description, :createByUser)";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':title', $ticket->title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $ticket->description, PDO::PARAM_STR);
    $stmt->bindParam(':createByUser', $session->username, PDO::PARAM_INT);

    return $stmt->execute();

}
