<?php
require_once __DIR__.'/../database/database.php';
require_once __DIR__.'/../database/client.db.php';
require_once __DIR__.'/../utils/session.php';
require_once __DIR__.'/../utils/routing.php';

$db = get_database();
header("Content-Type: application/json");

handle_api_route(
    "/labels",
    "GET",
    function () use ($db) {
        $query = ($_GET["q"] ?? '');

        //$labels = get_labels($query, $db);

        //echo json_encode($labels);
    }
);


handle_api_route(
    "/labels",
    "POST",
    function () use ($db) {
        // TODO: How can create labels???
        if (is_session_valid($db) === null) {
            http_response_code(403);
            echo '{"error":"user not authenticated"}';
            exit();
        }

        /*
        $client = get_user($id, $db);
        if ($client === null) {
            http_response_code(404);
            echo '{"error":"username not found"}';
            exit();
        }

        echo json_encode($client);
        */
    }
);

no_api_route();
