<?php
    require 'components/navbar/navbar.php';
    require 'database/database.php';
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
        $db = get_database();
        echo navbar($db);
    ?>
    <main class="ticket-page">
        <div>
            <h1>Creating a new Ticket</h1>
            <form method="POST" action="ticket">
                <div class="comment-box">
                    <h3>Title</h3>
                    <input type="text" placeholder="Title" name="title">
                    <h3>Description</h3>
                    <textarea cols="30" rows="10" placeholder="Describe your issue" name="description"></textarea>
                    <input type="submit" class="primary" value="Create" />
                </div>
            </form>
        </div>
    </main>
</body>
</html>
