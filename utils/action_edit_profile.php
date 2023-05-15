<?php

declare(strict_types=1);

require_once __DIR__.'/../database/client.db.php';


function edit_profile(string $username, string $email, string $displayName, PDO $db)
{
    $newClient = new Client($username, $email, '', $displayName, null);
    edit_user($newClient, $db);

}
