<?php


require_once __DIR__.'/../database/database.php';
require_once __DIR__.'/../database/department.db.php';
require_once __DIR__.'/../utils/session.php';
require_once __DIR__.'/../utils/roles.php';
require_once __DIR__.'/../utils/routing.php';



$db = get_database();
header("Content-Type: application/json");

handle_api_route(
    "/departments",
    "GET",
    function () use ($db) {
        if (is_session_valid($db) === null) {
            http_response_code(403);
            echo '{"error":"user not authenticated"}';
            exit();
        }

        $limit  = min(intval(($_GET["limit"] ?? 10)), 20);
        $offset = intval(($_GET["offset"] ?? 0));

        $departments = get_departments($limit, $offset, $db);

        echo json_encode($departments);
    }
);


handle_api_route(
    "/departments/<id>",
    "GET",
    function ($id) use ($db) {
        if (is_session_valid($db) === null) {
            http_response_code(403);
            echo '{"error":"user not authenticated"}';
            exit();
        }

        $limit  = min(intval(($_GET["limit"] ?? 10)), 20);
        $offset = intval(($_GET["offset"] ?? 0));

        $department = get_department($id, $db);

        if ($department === null) {
            http_response_code(404);
            echo '{"error":"department not found"}';
            exit();
        }

        echo json_encode($department);
    }
);

no_api_route();
