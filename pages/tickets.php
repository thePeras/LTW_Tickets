<?php
    require_once 'components/navbar/navbar.php';
    require_once 'components/ticket-card/ticket-card.php';
    require_once 'database/database.php';
    require_once 'database/tickets.db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets</title>
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/remixicon.css">
    <link rel="stylesheet" href="css/tickets.css">
</head>
<body>

<?php
    $db = get_database();
    echo navbar($db);

    $session = is_session_valid($db);
    $client  = null;

if ($session !== null) {
    $client = get_user($session->username, $db);
}



    $tab = ($_GET["tab"] ?? "assignedToMe");

    $tickets = [];

if ($tab === "unassigned" || $tab === null) {
    $tickets = getUnassignedTickets($db);
} else if ($tab === "assignedToMe") {
    $tickets = getTicketsAssignedTo($client->username, $db);
} else if ($tab === "createdByMe") {
    $tickets = getTicketsCreatedBy($client->username, $db);
} else if ($tab === "allTickets") {
    $tickets = getAllTickets($db);
} else if ($tab === "archived") {
    $tickets = getArchivedTickets($db);
}
?>
<main>
    <h1>Tickets</h1>
    <ul id = "buttons">
        <li><button type = "button"> Sort by</button></li>
        <li><button type = "button" class = "active"> New ticket</button></li>
    </ul>
    <ul class = "tabSelector" id = "filters">
        <li <?php
        if ($tab === "unassigned" || $tab === null) {
            echo 'class = "active"';
        }?>>
            <a href = "?tab=unassigned">Unassigned</a>
        </li>
        <li <?php
        if ($tab === "assignedToMe") {
            echo 'class = "active"';
        }?>>
            <a href = "?tab=assignedToMe">Assigned to me</a>
        </li>

        <li <?php
        if ($tab === "createdByMe") {
            echo 'class = "active"';
        }?>>
            <a href = "?tab=createdByMe">Created by me</a>
        </li>

        <li <?php
        if ($tab === "allTickets") {
            echo 'class = "active"';
        }?>>
            <a href = "?tab=allTickets">All tickets</a>
        </li>

        <li <?php
        if ($tab === "archived") {
            echo 'class = "active"';
        }?>>
            <a href = "?tab=archived">Archived</a>
        </li>
    </ul>

    <?php
    foreach ($tickets as $ticket) {
        echo ticketCard($ticket);
    }
    ?>

</main>
    
</body>
</html>
