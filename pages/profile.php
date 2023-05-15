<?php
    require 'components/navbar/navbar.php';
    require 'database/database.php';
    require 'utils/action_edit_profile.php';
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $displayName = $_POST['displayName'];
    $email       = $_POST['email'];
    edit_profile($client->username, $email, $displayName, $db);
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
            <form action="profile" method="post">
                <label for="email">Email:                 
                    <input type="email" name="email" id="email" value="<?php echo $client->email; ?>">
                </label>
                <label for="displayName">Display Name:                 
                    <input type="text" name="displayName" id="displayName" value="<?php echo $client->displayName; ?>">
                </label>
                <input type="submit" value="Save">
            </form>
        </div>
    </div>
</main>

</main>
</body>
</html>
