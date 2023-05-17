<?php

declare(strict_types=1);

require_once __DIR__.'/../database/client.db.php';
require 'utils/hash.php';


function edit_profile(Client $oldUser,
    $newUsername,
    $newEmail,
    ?string $newPassword,
    $newDisplayName,
    $newImage,
    PDO $db
) : bool {

    if ($newUsername !== null && $newUsername !== $oldUser->username) {
        if (user_already_exists($newUsername, $db) === true) {
            return false;
        }
    }

    if ($newImage['tmp_name'] !== '') {
        $newImageContent = file_get_contents($newImage['tmp_name']);
        if ($oldUser->image === Client::DEFAULT_IMAGE) {
            $newImageName     = hash_profile_picture($newUsername).'.png';
            $newImageLocation = __DIR__.'/../user_data/profile_pictures/'.$newImageName;
            file_put_contents($newImageLocation, $newImageContent);
            $newImage = '/user_data/profile_pictures/'.$newImageName;
        } else {
            file_put_contents(__DIR__.'/../'.$oldUser->image, $newImageContent);
            $newImage = $oldUser->image;
        }
    } else {
        $newImage = $oldUser->image;
    }

    if ($newPassword !== null) {
        $newPassword = hash_password($newPassword);
    } else {
        $newPassword = $oldUser->password;
    }

    $newUser = new Client($newUsername, $newDisplayName, $newEmail, $newPassword, $newImage);

    if (edit_user($oldUser, $newUser, $db) === false) {
        return false;
    }

    return change_sessions_username($oldUser->username, $newUsername, $db);

}
