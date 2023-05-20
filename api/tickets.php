<?php

require_once __DIR__.'/../database/database.php';
require_once __DIR__.'/../database/tickets.db.php';
require_once __DIR__.'/../utils/session.php';
require_once __DIR__.'/../utils/roles.php';
require_once __DIR__.'/../utils/routing.php';

$db = get_database();
header("Content-Type: application/json");

handle_api_route(
    "/tickets",
    "GET",
    function () use ($db) {
        if (is_session_valid($db) === null) {
            http_response_code(403);

            echo '{"error":"user not authenticated"}';
            exit();
        }

        $session = is_session_valid($db);
        $client  = get_user($session->username, $db);

        $limit  = min(intval(($_GET["limit"] ?? 4)), 20);
        $offset = intval(($_GET["offset"] ?? 0));

        $sortOrder = ($_GET["sortOrder"] ?? 'DESC');
        if ($sortOrder !== 'ASC' && $sortOrder !== 'DESC') {
            $sortOrder = 'DESC';
        }

        $text = ($_GET["text"] ?? '');
        $text = "%$text%";

        $tab = ($_GET["tab"] ?? "unassigned");
        if ($tab === "unassigned" || $tab === null) {
            $tickets = getUnassignedTickets($db, $limit, $offset, $sortOrder, $text);
        } else if ($tab === "assignedToMe") {
            $tickets = getTicketsAssignedTo($client->username, $db, $limit, $offset, $sortOrder, $text);
        } else if ($tab === "createdByMe") {
            $tickets = getTicketsCreatedBy($client->username, $db, $limit, $offset, $sortOrder, $text);
        } else if ($tab === "allTickets") {
            $tickets = getAllTickets($db, $limit, $offset, $sortOrder, $text);
        } else if ($tab === "archived") {
            $tickets = getArchivedTickets($db, $limit, $offset, $sortOrder, $text);
        }

        echo json_encode($tickets);
    }
);

no_api_route();
