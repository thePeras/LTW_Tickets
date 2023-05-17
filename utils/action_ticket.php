<?php
declare(strict_types=1);

require_once __DIR__.'/../database/tickets.db.php';
require_once __DIR__.'/../database/comments.db.php';


function create_ticket(string $title, string $description, PDO $db) : bool
{

    $ticket  = new Ticket($title, $description, time());
    $session = is_session_valid($db);

    $ticketId = insert_new_ticket($session, $ticket, $db);

    if ($ticketId === 0) {
        return false;
    }

    header('Location: /ticket?id='.$ticketId);
    return true;

}


function create_comment(string $content, int $ticketId, PDO $db) : bool
{

    $session = is_session_valid($db);

    $comment   = new Comment($content, $ticketId, $session->username, time());
    $commentId = insert_new_comment($comment, $db);

    if ($commentId === null) {
        return false;
    }

    return true;

}


function get_comments(int $ticketId, PDO $db) : array
{

    return get_comments_by_ticket($ticketId, $db);

}


function get_ticket_author(string $username, PDO $db) : ?Client
{

    return get_user($username, $db);

}
