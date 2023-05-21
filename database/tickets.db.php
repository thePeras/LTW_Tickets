<?php

declare(strict_types=1);

class Ticket
{

    public readonly int $id;

    public readonly string $title;

    public readonly string $description;

    public readonly string $status;

    public readonly string $hashtags;

    public readonly ?string $assignee;

    public readonly string $createdByUser;

    public readonly string $department;

    public int $createdAt;


    public function jsonSerialize() : mixed
    {
        return [
            "id"            => $this->id,
            "title"         => $this->title,
            "description"   => $this->description,
            "status"        => $this->status,
            "hashtags"      => $this->hashtags,
            "assignee"      => $this->assignee,
            "createdByUser" => $this->createdByUser,
            "createdAt"     => $this->createdAt,
            "timeAgo"       => $this->getTimeAgo(),
            "department"    => $this->department,

        ];

    }


    public function __construct($id, string $title, string $description, string $status,
        string $hashtags, ?string $assignee, string $createdByUser, string $department, ?int $_createdAt=null
    ) {
        $this->id            = $id;
        $this->title         = $title;
        $this->description   = $description;
        $this->status        = $status;
        $this->hashtags      = $hashtags;
        $this->assignee      = ($assignee ?? null);
        $this->createdByUser = $createdByUser;
        $this->createdAt     = ($_createdAt ?? time());
        $this->department    = $department;

    }


    public function getTimeAgo(): string
    {
        $createdAt = new DateTime('@'.$this->createdAt);
        $now       = new DateTime('now');
        $interval  = $createdAt->diff($now);

        $timeAgo = '';
        if ($interval->y > 0) {
            $timeAgo = $interval->format('%y year(s) ago');
        } elseif ($interval->m > 0) {
            $timeAgo = $interval->format('%m month(s) ago');
        } elseif ($interval->d > 0) {
            $timeAgo = $interval->format('%d day(s) ago');
        } elseif ($interval->h > 0) {
            $timeAgo = $interval->format('%h hour(s) ago');
        } elseif ($interval->i > 0) {
            $timeAgo = $interval->format('%i minute(s) ago');
        } elseif ($interval->s > 0) {
            $timeAgo = $interval->format('%s second(s) ago');
        }

        return $timeAgo;

    }


}


function getUnassignedTickets(PDO $db, $limit, $offset, $sortOrder, $text): array
{
    $text = "%$text%";
    $sql  = "SELECT * FROM Tickets WHERE assignee IS NULL AND status != 'archived' AND (id LIKE :text OR title LIKE :text OR description LIKE :text) ORDER BY createdAt $sortOrder LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":text", $text, PDO::PARAM_STR);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

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
            $ticket["department"],
            $ticket["createdAt"]
        );
    }

    return $tickets;

}


function getTicketsAssignedTo(string $username, PDO $db, $limit, $offset, $sortOrder, $text): array
{
    $text = "%$text%";
    $sql  = "SELECT * FROM tickets WHERE assignee = :username AND status != 'archived' AND (id LIKE :text OR title LIKE :text OR description LIKE :text) ORDER BY createdAt $sortOrder LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":text", $text, PDO::PARAM_STR);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
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
            $ticket["department"],
            $ticket["createdAt"]
        );
    }

    return $tickets;

}


function getTicketsCreatedBy(string $username, PDO $db, $limit, $offset, $sortOrder, $text): array
{
    $text = "%$text%";
    $sql  = "SELECT * FROM Tickets WHERE createdByUser = :username AND status != 'archived' AND (id LIKE :text OR title LIKE :text OR description LIKE :text) ORDER BY createdAt $sortOrder LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":text", $text, PDO::PARAM_STR);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
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
            $ticket["department"],
            $ticket["createdAt"]
        );
    }

    return $tickets;

}


function getAllTickets(PDO $db, $limit, $offset, $sortOrder, $text): array
{
    $text = "%$text%";
    $sql  = "SELECT * FROM tickets WHERE (id LIKE :text OR title LIKE :text OR description LIKE :text) ORDER BY createdAt $sortOrder LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":text", $text, PDO::PARAM_STR);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
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
            $ticket["department"],
            $ticket["createdAt"]
        );
    }

    return $tickets;

}


function getArchivedTickets(PDO $db, $limit, $offset, $sortOrder, $text): array
{
    $text = "%$text%";
    $sql  = "SELECT * FROM tickets WHERE status = 'archived' AND (id LIKE :text OR title LIKE :text OR description LIKE :text) ORDER BY createdAt $sortOrder LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":text", $text, PDO::PARAM_STR);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
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
            $ticket["department"],
            $ticket["createdAt"]
        );
    }

    return $tickets;

}
