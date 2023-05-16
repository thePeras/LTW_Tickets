<?php
declare(strict_types=1);

require_once __DIR__.'/../database/tickets.db.php';


function create_ticket(string $title, string $description, PDO $db) : bool
{
    $ticket  = new Ticket($title, $description);
    $session = is_session_valid($db);

    $ticketId = insert_new_ticket($session, $ticket, $db);

    if ($ticketId === 0) {
        return false;
    }

    header('Location: /ticket?id='.$ticketId);
    return true;

}
