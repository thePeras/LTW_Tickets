<?php
    require 'components/navbar/navbar.php';
    require 'database/database.php';
    //require 'utils/action_logout.php';
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
</head>
<body>

<?php
    $db = get_database();
    //logout($db);
    echo navbar($db);
?>
<main>
    <h1>Tickets</h1>
    <p>Content to be added</p>
</main>
    
</body>
</html>
