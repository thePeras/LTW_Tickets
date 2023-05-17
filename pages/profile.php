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
    <script src="js/profile_page.js" defer></script>
</head>
<body>

<?php
    $db = get_database();

    global $ifHtml;
    $session = is_session_valid($db);
    $client  = null;
if ($session !== null) {
    $client = get_user($session->username, $db);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username    = $_POST['username'];
    $displayName = $_POST['displayName'];
    $email       = $_POST['email'];
    $image       = $_FILES['fileInput'];

    if (edit_profile($client, $username, $email, null, $displayName, $image, $db) === true) {
        $client = get_user($username, $db);
    }
}

echo navbar($db);


?>
<main>
    <h1>Your Profile</h1>
    <div class="profile-container">
        <div class="image-container">
            <?php if ($client->image !== null) : ?>
                <img src="<?php echo $client->image ?>" alt="Client Image" id = "profilePicture">
            <?php else : ?>
                <img src="assets/images/default_user.png" alt="Default User Image" id = "profilePicture">
            <?php endif; ?>
        </div>
        <form action="profile" method="post" enctype="multipart/form-data" onsubmit="return handleSaveClick()">
            <label for="displayName">Name
                <input type="text" name="displayName" id="displayName" value="<?php echo $client->displayName; ?>" autocomplete="off" required readonly>
            </label>
            <label for="username">Username
                <input type="text" name="username" id="username" value="<?php echo $client->username; ?>" autocomplete="off" required readonly pattern="^[a-zA-Z0-9_\-\.]{3,20}$">
            </label>
            <label for="email">Email
                <input type="email" name="email" id="email" value="<?php echo $client->email; ?>" required readonly>
            </label>
            <input type="button" id="editButton" value="Edit">
            <div id = "editButtons">
                <input type="button" id="cancelButton" value="Cancel" style="display: none;" onclick = "handleCancelClick()">
                <input type="submit" id="saveButton" value="Save" style="display: none;">
            </div>
            <input type="file" name="fileInput" id="fileInput" style="display: none;" accept="image/*" onchange="handleFileSelect(event)">
        </form>
    </div>
</main>

</main>
</body>
</html>
