<?php
    require 'components/navbar/navbar.php';
    require 'database/database.php';
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
    <link rel="stylesheet" href="css/profile_page.css">
</head>
<body>

<?php
    $db = get_database();
    echo navbar($db);

    global $ifHtml;
    $session = is_session_valid($db);
    $client  = null;
if ($session !== null) {
    $client = get_user($session->username, $db);
}

?>
<main>
    <h1>Your Profile</h1>
    <div class="profile-container">
        <div class="image-container">
            <?php if ($client->image !== null) : ?>
                <img src="<?php echo base64_encode($client->image); ?>" alt="Client Image">
            <?php else : ?>
                <img src="assets/images/default_user.png" alt="Default User Image">
            <?php endif; ?>
        </div>
        <div class="fields-container">
            <p>Username: <?php echo $client->username; ?></p>
            <p>Email: <?php echo $client->email; ?></p>
            <p>Display Name: <?php echo $client->displayName; ?></p>
        </div>
    </div>
</main>

</main>
</body>
</html>
