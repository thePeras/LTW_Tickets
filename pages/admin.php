<?php
require_once __DIR__.'/../database/database.php';
require_once __DIR__.'/../utils/roles.php';
require_once __DIR__.'/../utils/session.php';
require_once __DIR__.'/../components/navbar/navbar.php';
require_once __DIR__.'/../database/client.db.php';
require_once __DIR__.'/../database/department.db.php';
require_once __DIR__.'/../components/user-table/user-table.php';
require_once __DIR__.'/../components/department-table/department-table.php';




$db      = get_database();
$session = is_session_valid($db);
if ($session === null) {
    header("Location: /login");
    exit();
}

if (is_current_user_admin($db) === false) {
    header("Location: /");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //TODO: check for csrf token
    if (isset($_POST["action"]) === false) {
        header("Location: /");
        exit();
    }

    if ($_POST["action"] === "deleteUser" && isset($_POST["username"]) === true) {
        if ($_POST["username"] !== $session->username) {
            delete_client($_POST["username"], $db);
        }

        //TODO: make error message
    }

    if ($_POST["action"] === "editUser" && isset($_POST["username"]) === true) {
        if (update_user($_POST["username"], $_POST["displayName"], ($_POST["password"] ?? ""), $_POST["email"], $_POST["role"], $db) === false) {
            log_to_stdout("Error while updating user ".$_POST["username"], "e");
            //TODO: make error message
        }
    }

    if ($_POST["action"] === "newDepartment") {
        $members = explode(",", ($_POST["members"] ?? ''));
        if ($members === false || (count($members) === 1 && $members[0] === '')) {
            $members = [];
        }

        add_department($_POST["name"], $_POST["description"], $members, $db);
    }


    if ($_POST["action"] === "editDepartment") {
        $members = explode(",", ($_POST["members"] ?? ''));
        if ($members === false || (count($members) === 1 && $members[0] === '')) {
            $members = [];
        }

        edit_department($_POST["name"], $_POST["description"], $members, $db);
    }

    if ($_POST["action"] === "deleteDepartment") {
        delete_department($_POST["name"], $db);
    }


    if (isset($_POST["lastHref"]) === true) {
        header("Location: ".$_POST["lastHref"]);
    } else {
        header("Location: /admin");
    }

    exit();
}

$limit  = min(intval(($_GET["limit"] ?? 10)), 20);
$offset = intval(($_GET["offset"] ?? 0));
$tab    = ($_GET["tab"] ?? "users");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets</title>
    <link rel="stylesheet" href="/css/layout.css">
    <link rel="stylesheet" href="/css/theme.css">
    <link rel="stylesheet" href="/css/remixicon.css">
</head>
<body>
    <?php
    navbar($db);

    ?>
<main>
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/modal.css">
    <link rel="stylesheet" href="/css/dropdown.css">
    <link rel="stylesheet" href="/css/components.css">
    <script src="/js/modal.js"></script>


    <h1>Admin page</h1>
    <ul class="tabSelector">
        <li <?php
        if ($tab === "users" || $tab === null) {
            echo 'class="active"';
        }?>>
            <a href="?tab=users">Users</a>
        </li>
        <li
        <?php
        if ($tab === "departments") {
            echo 'class="active"';
        }?>>
            <a href="?tab=departments">Departments</a>
        </li>
    </ul>

    <?php if ($tab === "users" || $_GET["tab"] === null) :?>
        <?php
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
        ?>
        <script src="/js/user-table.js"></script>

        <?php
        drawUserTable($clients);
        elseif ($tab === "departments") :
            $departments = get_departments($limit, $offset, $db, false);
            ?>
            <script src="/js/department.js"></script>

            <div class="department-buttons">
                <button onclick="makeAddDepartmentModal()" class="add-new">Add new...</button>
            </div>
            <?php drawDepartmentTable($departments);
        endif;?>
        
</main>    
</body>
</html>
