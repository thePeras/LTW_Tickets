<?php

declare(strict_types=1);

require_once __DIR__.'/../database/faq.db.php';
require_once "utils/datetime.php";

class Ticket implements JsonSerializable
{

    public int $id;

    public string $title;

    public string $description;

    public string $status;

    public string $hashtags;

    public Client $assignee;

    public string $createdByUser;

    public string $department;

    public Datetime $createdAt;

    public FAQ $faq;


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
            "timeAgo"       => time_ago($this->createdAt),
            "department"    => $this->department,
        ];

    }


    public function __construct(string $title, string $description, int $_epoch, $id=0, string $status="",
        string $hashtags="", Client $assignee=new Client(""), string $createdByUser="", string $department="", FAQ $faq=new FAQ(0)
    ) {
        $this->id            = $id;
        $this->title         = $title;
        $this->description   = $description;
        $this->status        = $status;
        $this->hashtags      = $hashtags;
        $this->assignee      = ($assignee ?? null);
        $this->createdByUser = $createdByUser;
        $this->department    = $department;
        $this->createdAt     = new DateTime("@".$_epoch);
        $this->faq           = $faq;

    }


}


function getUnassignedTickets(PDO $db, $limit, $offset, $sortOrder, $text): array
{
    $text = "%$text%";

    $sql  = "SELECT * FROM Tickets WHERE assignee IS NULL AND (status != 'closed' OR status IS NULL) AND (id LIKE :text OR title LIKE :text OR description LIKE :text) ORDER BY createdAt $sortOrder LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":text", $text, PDO::PARAM_STR);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tickets = [];

    foreach ($result as $ticket) {
        $tickets[] = new Ticket(
            $ticket['title'],
            $ticket['description'],
            (int) $ticket['createdAt'],
            (int) $ticket['id'],
            ($ticket['status'] ?? ""),
            ($ticket['hashtags'] ?? ""),
            (get_user($ticket['assignee'], $db)),
            ($ticket['createdByUser'] ?? ""),
            ($ticket['department'] ?? ""),
            (get_faq((int) $ticket['faq'], $db) ?? new FAQ(0))
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
            $ticket['title'],
            $ticket['description'],
            (int) $ticket['createdAt'],
            (int) $ticket['id'],
            ($ticket['status'] ?? ""),
            ($ticket['hashtags'] ?? ""),
            (get_user($ticket['assignee'], $db)),
            ($ticket['createdByUser'] ?? ""),
            ($ticket['department'] ?? ""),
            (get_faq((int) $ticket['faq'], $db) ?? new FAQ(0))
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
            $ticket['title'],
            $ticket['description'],
            (int) $ticket['createdAt'],
            (int) $ticket['id'],
            ($ticket['status'] ?? ""),
            ($ticket['hashtags'] ?? ""),
            (get_user($ticket['assignee'], $db)),
            ($ticket['createdByUser'] ?? ""),
            ($ticket['department'] ?? ""),
            (get_faq((int) $ticket['faq'], $db) ?? new FAQ(0))
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
            $ticket['title'],
            $ticket['description'],
            (int) $ticket['createdAt'],
            (int) $ticket['id'],
            ($ticket['status'] ?? ""),
            ($ticket['hashtags'] ?? ""),
            (get_user($ticket['assignee'], $db)),
            ($ticket['createdByUser'] ?? ""),
            ($ticket['department'] ?? ""),
            (get_faq((int) $ticket['faq'], $db) ?? new FAQ(0))
        );
    }

    return $tickets;

}


function getArchivedTickets(PDO $db, $limit, $offset, $sortOrder, $text): array
{
    $text = "%$text%";
    $sql  = "SELECT * FROM tickets WHERE status = 'closed' AND (id LIKE :text OR title LIKE :text OR description LIKE :text) ORDER BY createdAt $sortOrder LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":text", $text, PDO::PARAM_STR);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tickets = [];

    foreach ($result as $ticket) {
        $tickets[] = new Ticket(
            $ticket['title'],
            $ticket['description'],
            (int) $ticket['createdAt'],
            (int) $ticket['id'],
            ($ticket['status'] ?? ""),
            ($ticket['hashtags'] ?? ""),
            (get_user($ticket['assignee'], $db)),
            ($ticket['createdByUser'] ?? ""),
            ($ticket['department'] ?? ""),
            (get_faq((int) $ticket['faq'], $db) ?? new FAQ(0))
        );
    }

    return $tickets;

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
    $sql  = "SELECT * FROM Clients RIGHT JOIN Tickets ON Clients.username = Tickets.assignee WHERE id = :id";
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
        new Client(($result['username'] ?? ""), null, null, ($result['displayName'] ?? null), ($result['image'] ?? null)),
        ($result['createdByUser'] ?? ""),
        ($result['department'] ?? ""),
        (get_faq((int) $result['faq'], $db) ?? new FAQ(0))
    );

}


function update_ticket_department(Ticket $ticket, PDO $db) : bool
{
    if ($ticket->department === "") {
        $sql = "UPDATE Tickets SET department = NULL WHERE id = :id";
    } else {
        $sql = "UPDATE Tickets SET department = :department WHERE id = :id";
    }

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $ticket->id, PDO::PARAM_INT);

    if ($ticket->department !== "") {
        $stmt->bindParam(':department', $ticket->department, PDO::PARAM_STR);
    }

    return $stmt->execute();

}


function update_ticket_status(Ticket $ticket, PDO $db) : bool
{
    if ($ticket->status === "") {
        $sql  = "UPDATE Tickets SET status = NULL, faq = NULL WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $ticket->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    if ($ticket->faq->id !== 0) {
        $sql  = "UPDATE Tickets SET status = :status, faq = :faq WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $ticket->id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $ticket->status, PDO::PARAM_STR);
        $stmt->bindParam(':faq', $ticket->faq->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    $sql  = "UPDATE Tickets SET status = :status WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $ticket->id, PDO::PARAM_INT);
    $stmt->bindParam(':status', $ticket->status, PDO::PARAM_STR);
    return $stmt->execute();

}


function update_ticket_assignee(Ticket $ticket, PDO $db) : bool
{
    if ($ticket->assignee->username === "") {
        $sql  = "UPDATE Tickets SET assignee = NULL WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $ticket->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    $sql  = "UPDATE Tickets SET assignee = :assignee WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $ticket->id, PDO::PARAM_INT);
    $stmt->bindParam(':assignee', $ticket->assignee->username, PDO::PARAM_STR);
    return $stmt->execute();

}
