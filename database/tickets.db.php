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


    public function __construct($id, string $title, string $description, string $status,
        string $hashtags, string $assignee, string $createdByUser, string $department
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


function getTicketsCreatedBy(string $username, PDO $db) : array
{
    $stmt = $db->prepare("SELECT * FROM tickets WHERE createdByUser = :username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tickets = [];

    foreach ($result as $ticket) {
        $tickets[] = new Ticket(
            $ticket["id"],
            $ticket["title"],
            $ticket["description"],
            $ticket["status"],
            $ticket["hashtags"],
            $ticket["assignee"],
            $ticket["createdByUser"],
            $ticket["department"]
        );
    }

    return $tickets;

}


function getTicketsAssignedTo(string $username, PDO $db) : array
{
    $stmt = $db->prepare("SELECT * FROM tickets WHERE assignee = :username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tickets = [];

    foreach ($result as $ticket) {
        $tickets[] = new Ticket(
            $ticket["id"],
            $ticket["title"],
            $ticket["description"],
            $ticket["status"],
            $ticket["hashtags"],
            $ticket["assignee"],
            $ticket["createdByUser"],
            $ticket["department"]
        );
    }

    return $tickets;

}


function getUnassignedTickets(PDO $db) : array
{
    $stmt = $db->prepare("SELECT * FROM tickets WHERE assignee = NULL");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tickets = [];

    foreach ($result as $ticket) {
        $tickets[] = new Ticket(
            $ticket["id"],
            $ticket["title"],
            $ticket["description"],
            $ticket["status"],
            $ticket["hashtags"],
            $ticket["assignee"],
            $ticket["createdByUser"],
            $ticket["department"]
        );
    }

    return $tickets;

}


function getArchivedTickets(PDO $db) : array
{
    $stmt = $db->prepare("SELECT * FROM tickets WHERE status = 'archived'");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tickets = [];

    foreach ($result as $ticket) {
        $tickets[] = new Ticket(
            $ticket["id"],
            $ticket["title"],
            $ticket["description"],
            $ticket["status"],
            $ticket["hashtags"],
            $ticket["assignee"],
            $ticket["createdByUser"],
            $ticket["department"]
        );
    }

    return $tickets;

}


function getAllTickets(PDO $db) : array
{
    $stmt = $db->prepare("SELECT * FROM tickets");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tickets = [];

    foreach ($result as $ticket) {
        $tickets[] = new Ticket(
            $ticket["id"],
            $ticket["title"],
            $ticket["description"],
            $ticket["status"],
            $ticket["hashtags"],
            $ticket["assignee"],
            $ticket["createdByUser"],
            $ticket["department"]
        );
    }

    return $tickets;

}
