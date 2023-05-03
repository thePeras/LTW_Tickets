<?php
    require 'components/navbar/navbar.php';

    $id = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #2</title>
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/remixicon.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/ticket.css">
</head>
<body>
    <?php
        echo navbar();
    ?>
    <main class="ticket-page">
        <div>
            <h1>Ticket #2</h1>
            <ul id = "buttons">
                <li><button type = "button"> Status </button></li>
                <li><button type = "button" class = "active"> Close </button></li>
            </ul>
        </div>
        <div class="action-panel">
            <div class="side-card">
                <div>
                    <h4 class="task-label">Assignee</h4>
                    <p>
                        <i class="ri-account-circle-line"></i>
                        Unassigned
                    </p>
                </div>

                <div>
                    <h4 class="task-label">Team</h4>
                    <p>
                        <i class="ri-group-2-line"></i> 
                        Unassigned
                    </p>
                </div>
            </div>
            <div class="side-card">
                <h4 class="task-label">labels</h4>
                <div>

                </div>
            </div>
        </div>
    </main>
</body>
</html>
