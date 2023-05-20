<?php
declare(strict_types=1);

require_once __DIR__.'/../database/tickets.db.php';
require_once __DIR__.'/../database/comments.db.php';
require_once __DIR__.'/../database/department.db.php';
require_once __DIR__.'/../database/changes.db.php';


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

    $createdByUser = new Client($session->username);
    $comment       = new Comment($content, $ticketId, $createdByUser, time());
    $commentId     = insert_new_comment($comment, $db);

    if ($commentId === null) {
        return false;
    }

    return true;

}


function get_comments(int $ticketId, PDO $db) : array
{

    return get_comments_by_ticket($ticketId, $db);

}


function get_changes(int $ticketId, PDO $db) : array
{

    return get_changes_by_ticket($ticketId, $db);

}


function get_ticket_author(string $username, PDO $db) : ?Client
{

    return get_user($username, $db);

}


function change_department(int $ticketId, string $department, PDO $db) : ?string
{

    $ticket = get_ticket($ticketId, $db);
    if ($ticket === null) {
        return 'Ticket not found';
    }

    // Reseting department
    if ($department === '') {
        if (remove_ticket_department($ticket, $db) !== false) {
            return 'Error updating ticket';
        }

        return null;
    }

    $department = get_department($department, $db);
    if ($department === null) {
        return 'Department not found';
    }

    $ticket->department = $department->name;

    if (update_ticket_department($ticket, $db) !== true) {
        return 'Error updating ticket';
    }

    return null;

}


function close_ticket(int $ticketId, PDO $db) : ?string
{

    $ticket = get_ticket($ticketId, $db);
    if ($ticket === null) {
        return 'Ticket not found';
    }

    if ($ticket->status === 'closed') {
        return 'Ticket is already closed';
    }

    $ticket->status = 'closed';

    if (update_ticket_status($ticket, $db) !== true) {
        return 'Error updating ticket';
    }

    // Creating a change
    $changeBy = new Client($ticket->createdByUser, '', '');
    $changeAt = time();
    $change   = new StatusChange(0, $ticket->id, $changeAt, $changeBy, $ticket->status);
    if (insert_new_change($change, $db) === null) {
        return 'Error inserting the change';
    }

    return null;

}


function assign_ticket($ticketId, $user, $db)
{
    $ticket = get_ticket($ticketId, $db);
    if ($ticket === null) {
        return 'Ticket not found';
    }

    $user = get_user($user, $db);
    if ($user === null) {
        return 'User not found';
    }

    $ticket->assignee = $user;

    if (update_ticket_assignee($ticket, $db) !== true) {
        return 'Error updating ticket';
    }

    // Creating a change
    $changeBy = new Client($ticket->createdByUser, '', '');
    $changeAt = time();
    $change   = new AssignedChange(0, $ticket->id, $changeAt, $changeBy, $ticket->assignee);
    if (insert_new_change($change, $db) === null) {
        return 'Error inserting the change';
    }

    return null;

}


function unassign_ticket($ticketId, $db)
{
    $ticket = get_ticket($ticketId, $db);
    if ($ticket === null) {
        return 'Ticket not found';
    }

    $ticket->assignee = new Client('');

    if (update_ticket_assignee($ticket, $db) !== true) {
        return 'Error updating ticket';
    }

    // Creating a change
    $changeBy = new Client($ticket->createdByUser, '', ''); //TODO: Change to the user that unassigned the ticket
    $changeAt = time();
    $change   = new AssignedChange(0, $ticket->id, $changeAt, $changeBy, $ticket->assignee);
    if (insert_new_change($change, $db) === null) {
        return 'Error inserting the change';
    }

    return null;

}
