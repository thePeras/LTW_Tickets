<?php
require_once __DIR__.'/../database/database.php';
require_once __DIR__.'/../utils/roles.php';
require_once __DIR__.'/../utils/session.php';
require_once __DIR__.'/../components/navbar/navbar.php';


$db = get_database();

if (is_session_valid($db) === false) {
    header("Location: /login");
    exit();
}

if (is_current_user_admin($db) === false) {
    header("Location: /");
    exit();
}
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
    navbar($db);
?>
<main>
    <h1>Admin page</h1>
    <p>Siuuu</p>
</main>
    
</body>
</html>
