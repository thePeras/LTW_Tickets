<?php
    require_once 'components/navbar/navbar.php';
    require_once 'components/ticket-card/ticket-card.php';
    require_once 'database/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets</title>
    <link rel="stylesheet" href="/css/layout.css">
    <link rel="stylesheet" href="/css/theme.css">
    <link rel="stylesheet" href="/css/remixicon.css">
    <link rel="stylesheet" href="pages/tickets.css">
</head>
<body>

<?php
    $db = get_database();
    echo navbar($db);
?>
<main>
    <h1>Tickets</h1>
    <ul id = "buttons">
        <li><button type = "button"> Sort by</button></li>
        <li><button type = "button" class = "active"> New ticket</button></li>
    </ul>
    <ul id = "filters">
        <li class = "active">Unassigned</li>
        <li>Assigned to me</li>
        <li>All tickets</li>
        <li>Archived</li>
    </ul>
    <?php echo ticketCard(); ?>
</main>
    
</body>
</html>
