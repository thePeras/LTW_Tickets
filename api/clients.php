<?php
require_once __DIR__.'/../database/database.php';
require_once __DIR__.'/../database/client.db.php';
require_once __DIR__.'/../utils/session.php';
require_once __DIR__.'/../utils/roles.php';

$db = get_database();
header("Content-Type: application/json");
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (is_session_valid($db) === null) {
        http_response_code(403);
        echo '{"error":"user not authenticated"}';
        exit();
    }

    $limit  = min(intval(($_GET["limit"] ?? 10)), 20);
    $offset = intval(($_GET["offset"] ?? 0));

    $clients = get_clients($limit, $offset, $db);

    echo json_encode($clients);
}
