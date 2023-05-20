<?php
    require_once 'components/navbar/navbar.php';
    require_once 'components/ticket-card/ticket-card.php';
    require_once 'database/database.php';
    require_once 'database/tickets.db.php';
    require_once 'components/layout/layout.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets</title>
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/remixicon.css">
    <link rel="stylesheet" href="css/tickets.css">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="components/ticket-card/ticket-card.css">

    <script src="js/tickets.js"></script>
</head>
<body>

<?php
    $db = get_database();

    $session = is_session_valid($db);
    $client  = null;

if ($session !== null) {
    $client = get_user($session->username, $db);
}

layout_start();

    $tickets = [];

    $limit  = min(intval(($_GET["limit"] ?? 4)), 20);
    $offset = intval(($_GET["offset"] ?? 0));

    $tab = ($_GET["tab"] ?? "unassigned");

    $sortOrder = ($_GET["sortOrder"] ?? 'DESC');

if ($sortOrder !== 'ASC' && $sortOrder !== 'DESC') {
    $sortOrder = 'DESC';
}


if (isset($_GET['sortSelect']) === true) {
    $selectedSort = $_GET['sortSelect'];
    if ($selectedSort === 'lastCreated') {
        $sortOrder = 'DESC';
    } elseif ($selectedSort === 'firstCreated') {
        $sortOrder = 'ASC';
    }
}

if ($tab === "unassigned" || $tab === null) {
    $tickets = getUnassignedTickets($db, $limit, $offset, $sortOrder);
} else if ($tab === "assignedToMe") {
    $tickets = getTicketsAssignedTo($client->username, $db, $limit, $offset, $sortOrder);
} else if ($tab === "createdByMe") {
    $tickets = getTicketsCreatedBy($client->username, $db, $limit, $offset, $sortOrder);
} else if ($tab === "allTickets") {
    $tickets = getAllTickets($db, $limit, $offset, $sortOrder);
} else if ($tab === "archived") {
    $tickets = getArchivedTickets($db, $limit, $offset, $sortOrder);
}


?>
<main>
    <h1>Tickets</h1>
    <ul id="buttons">
    <li>
        <select id="sortSelect" onchange="handleSortOptionChange(this.value)">
            <option value="lastCreated" <?php if ($sortOrder === 'DESC') {
                echo 'selected';
} ?>>Last Created</option>
            <option value="firstCreated" <?php if ($sortOrder === 'ASC') {
                echo 'selected';
} ?>>First Created</option>
        </select>
    </li>
    <li><button type="button" class="active">New ticket</button></li>
</ul>


    <ul class="tabSelector" id="filters">
        <li <?php
        if ($tab === "unassigned" || $tab === null) {
            echo 'class="active"';
        } ?>>
            <a href="?tab=unassigned">Unassigned</a>
        </li>
        <li <?php
        if ($tab === "assignedToMe") {
            echo 'class="active"';
        } ?>>
            <a href="?tab=assignedToMe">Assigned to me</a>
        </li>

        <li <?php
        if ($tab === "createdByMe") {
            echo 'class="active"';
        } ?>>
            <a href="?tab=createdByMe">Created by me</a>
        </li>

        <li <?php
        if ($tab === "allTickets") {
            echo 'class="active"';
        } ?>>
            <a href="?tab=allTickets">All tickets</a>
        </li>

        <li <?php
        if ($tab === "archived") {
            echo 'class="active"';
        } ?>>
            <a href="?tab=archived">Archived</a>
        </li>
    </ul>

    <div class="ticket-list">
        <?php
        foreach ($tickets as $ticket) {
            echo ticketCard($ticket);
        }
        ?>
    </div>

</main>

</body>
</html>
