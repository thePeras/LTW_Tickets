<?php

declare(strict_types=1);

require_once __DIR__."/status.db.php";
require_once __DIR__."/labels.db.php";


class Ticket
{

    public readonly int $id;

    public readonly string $title;

    public readonly string $description;

    public readonly Status $status;

    public readonly array $labels;

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
            "status"        => json_encode($this->status),
            "labels"        => json_encode($this->labels),
            "assignee"      => $this->assignee,
            "createdByUser" => $this->createdByUser,
            "createdAt"     => $this->createdAt,
            "timeAgo"       => $this->getTimeAgo(),
            "department"    => $this->department,

        ];

    }


    public function __construct($id, string $title, string $description, Status $status,
        array $labels, ?string $assignee, string $createdByUser, string $department, ?int $_createdAt=null
    ) {
        $this->id            = $id;
        $this->title         = $title;
        $this->description   = $description;
        $this->status        = $status;
        $this->labels        = $labels;
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


function create_ticket_object(array $ticket, PDO $db) : Ticket
{
    $sql  = "SELECT * FROM Status WHERE status=:status";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":status", $ticket["status"]);
    $stmt->execute();

    $result = $stmt->fetch();

    $status = new Status(
        $result["status"],
        $result["color"],
        $result["backgroundColor"],
        $result["createdAt"]
    );

    $sql  = "SELECT * FROM LabelTicket l JOIN Labels h ON h.label=l.label WHERE ticket=:id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id", $ticket["id"], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll();

    $labels = array_map(
        function (array $a): Label {
            return new Label(
                $a["label"],
                $a["color"],
                $a["backgroundColor"],
                $a["createdAt"]
            );
        },
        $result
    );

    return new Ticket(
        $ticket["id"],
        $ticket["title"],
        $ticket["description"],
        $status,
        $labels,
        $ticket["assignee"],
        $ticket["createdByUser"],
        $ticket["department"],
        $ticket["createdAt"]
    );

};


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
        $tickets[] = create_ticket_object($ticket, $db);
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
        $tickets[] = create_ticket_object($ticket, $db);
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
        $tickets[] = create_ticket_object($ticket, $db);
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
        $tickets[] = create_ticket_object($ticket, $db);
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
        $tickets[] = create_ticket_object($ticket, $db);
    }

    return $tickets;

}
