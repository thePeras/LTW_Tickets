<?php
    require_once 'components/navbar/navbar.php';
    require_once 'database/database.php';
    require_once 'utils/action_edit_profile.php';
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
    $action = $_POST['action'];

    if ($action === "editProfile") {
        $displayName = $_POST['displayName'];
        $email       = $_POST['email'];
        $image       = $_FILES['fileInput'];

        if (edit_profile($client, $email, $displayName, $image, $db) === true) {
            $client = get_user($client->username, $db);
        }
    }

    if ($action === "changePassword") {
        $oldPassword     = $_POST['oldPassword'];
        $newPassword     = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        if (edit_password($client, $oldPassword, $newPassword, $confirmPassword, $db) === true) {
            $client = get_user($client->username, $db);
        }
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
            <label for="username">Username
                <input type="text" name="username" id="username" value="<?php echo $client->username; ?>" autocomplete="off" pattern="^[a-zA-Z0-9_\-\.]{3,20}$" disabled>
            </label>

            <label for="displayName">Name
                <input type="text" name="displayName" id="displayName" value="<?php echo $client->displayName; ?>" autocomplete="off" required readonly>
            </label>

            <label for="email">Email
                <input type="email" name="email" id="email" value="<?php echo $client->email; ?>" required readonly>
            </label>

            <input type="button" id="editButton" value="Edit">
            <div id = "editButtons">
                <input type="button" id="cancelButton" value="Cancel" style="display: none;" onclick = "handleCancelClick()">
                <input type="submit" id="saveButton" value="Save" style="display: none;">
            </div>

            <input type="hidden" name="action" value="editProfile">
            <input type="file" name="fileInput" id="fileInput" style="display: none;" accept="image/*" onchange="handleFileSelect(event)">
        </form>

        <form action = "profile" method = "post">
            <label for = "oldPassword">Your current password
                <input type="password" name = "oldPassword" id = "oldPassword" required>
            </label>

            <label for = "newPassword">New password
                <input type="password" name = "newPassword" id = "newPassword" required>
            </label>

            <label for = "confirmPassword">Confirm new password
                <input type="password" name = "confirmPassword" id = "confirmPassword" required>
            </label>

            <input type="hidden" name="action" value="changePassword">
            <input type="submit" value="Change Password">
        </form>

    </div>
</main>

</main>
</body>
</html>
