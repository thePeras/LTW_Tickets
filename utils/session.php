<?php

declare(strict_types=1);

require_once __DIR__.'/../database/session.db.php';

$expiryTime = (60 * 60 * 24);


function set_cookie_session(Session $session)
{
    global $expiryTime;
    setcookie('sessionID', $session->token, (time() + $expiryTime), '/', httponly:true);

}


function is_session_valid(PDO $db) : ?Session
{
    global $expiryTime;
    if (isset($_COOKIE['sessionID']) === false) {
        return null;
    }

    $session = get_session($_COOKIE['sessionID'], $db);
    if ($session === null) {
        return null;
    }

    if ($session->lastUsed->add(new DateInterval('PT'.$expiryTime.'S')) > new DateTime('now') === false) {
        remove_session($session->token, $db);
        return null;
    }

    return $session;

}
