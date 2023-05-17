<?php

declare(strict_types=1);

require_once __DIR__.'/../database/client.db.php';
require 'utils/hash.php';


function edit_profile(Client $oldUser,
    ?string $newUsername,
    ?string $newEmail,
    ?string $newPassword,
    ?string $newDisplayName,
    $newImage,
    PDO $db
) : bool {

    if ($newUsername !== null && $newUsername !== $oldUser->username) {
        if (user_already_exists($newUsername, $db) === true) {
            return false;
        }
    }

    if ($newImage['tmp_name'] !== '') {
        $newImageContent  = file_get_contents($newImage['tmp_name']);
        $newImageName     = hash_profile_picture($newUsername).'.png';
        $newImageLocation = __DIR__.'/../user_data/profile_pictures/'.$newImageName;
        file_put_contents($newImageLocation, $newImageContent);
        $newImage = '/user_data/profile_pictures/'.$newImageName;
    } else {
        $newImage = null;
    }

    if (edit_user($oldUser, $newUsername, $newEmail, $newPassword, $newDisplayName, $newImage, $db) === false) {
        return false;
    }

    return change_sessions_username($oldUser->username, $newUsername, $db);

}
