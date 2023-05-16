<?php
declare(strict_types=1);

require_once __DIR__.'/../utils/hash.php';
require_once __DIR__.'/../database/client.db.php';


function create_ticket(string $title, string $description, PDO $db) : bool
{
    $ticket = new Ticket($title, $description);
    if (insert_new_ticket($$_COOKIE['sessionID'], $ticket, $db) === false) {
        return false;
    }

    return true;

}
