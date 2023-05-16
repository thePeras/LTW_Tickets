<?php

declare(strict_types=1);

require_once __DIR__.'/../database/client.db.php';


function edit_profile(Client $oldUser,
    ?string $newUsername,
    ?string $newEmail,
    ?string $newPassword,
    ?string $newDisplayName,
    ?string $newImage,
    PDO $db
) : bool {

    if ($newUsername !== null && $newUsername !== $oldUser->username) {
        if (user_already_exists($newUsername, $db) === true) {
            return false;
        }
    }

    if (edit_user($oldUser, $newUsername, $newEmail, $newPassword, $newDisplayName, $newImage, $db) === false) {
        return false;
    }

    return change_sessions_username($oldUser->username, $newUsername, $db);

}
