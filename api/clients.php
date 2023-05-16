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

    $clients = [];
    if (isset($_GET["sort"]) === false) {
        $clients = get_clients($limit, $offset, $db);
    } else if ($_GET["sort"] === "client") {
        $clients = get_clients_only($limit, $offset, $db);
    } else if ($_GET["sort"] === "agent") {
        $clients = get_agents($limit, $offset, $db);
    } else if ($_GET["sort"] === "admin") {
        $clients = get_admins($limit, $offset, $db);
    }

    echo json_encode($clients);
}
