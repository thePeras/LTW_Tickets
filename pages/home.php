<?php
    require 'components/navbar/navbar.php';
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
    <link rel="stylesheet" href="pages/tickets.css">
</head>
<body>

<?php
    echo navbar();
?>
<main>
    <h1>Tickets</h1>
    <ul id = "buttons">
        <li><button type = "button"> Sort by</button></li>
        <li><button type = "button"> New ticket</button></li>
    </ul>
    <ul id = "filters">
        <li class = "active">Unassigned</li>
        <li>Assigned to me</li>
        <li>All tickets</li>
        <li>Archived</li>
    </ul>

    <div class = "ticket-card">
        <p>aqui está um ticket</p>
    </div>

    <div class = "ticket-card">
        <p>aqui está outro ticket</p>
    </div>
</main>
    
</body>
</html>
