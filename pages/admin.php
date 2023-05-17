<?php
require_once __DIR__.'/../database/database.php';
require_once __DIR__.'/../utils/roles.php';
require_once __DIR__.'/../utils/session.php';
require_once __DIR__.'/../components/navbar/navbar.php';
require_once __DIR__.'/../database/client.db.php';


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


    if (isset($_POST["lastHref"]) === true) {
        header("Location: ".$_POST["lastHref"]);
    } else {
        header("Location: /admin");
    }

    exit();
}

$limit  = min(intval(($_GET["limit"] ?? 10)), 20);
$offset = intval(($_GET["offset"] ?? 0))
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets</title>
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/remixicon.css">
</head>
<body>
<div class="modal">
        <div class="modal-content">

        </div>
    </div>
    <?php
    navbar($db);

    ?>
<main>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/dropdown.css">
    <link rel="stylesheet" href="css/components.css">


    <h1>Admin page</h1>
    <ul class="tabSelector">
        <li <?php
        if ($_GET["tab"] === "users" || $_GET["tab"] === null) {
            echo 'class="active"';
        }?>>
            <a href="?tab=users">Users</a>
        </li>
        <li
        <?php
        if ($_GET["tab"] === "departments") {
            echo 'class="active"';
        }?>>
            <a href="?tab=departments">Departments</a>
        </li>
    </ul>

    <?php if ($_GET["tab"] === "users" || $_GET["tab"] === null) :?>
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
        <script src="js/user-table.js"></script>
        <script src="js/modal.js"></script>


        
        
        <table class="user-table">
            <thead>
                <tr>
                    <th>
                        User
                    </th>
                    <th>
                        Email
                    </th>
                    <th>
                        Role
                    </th>
                    <th>
                        Date created
                    </th>
                    <th>
                        </th>
                        <th>
                            <div class="dropdown-hover">
                                <i class="ri-filter-line icon">
                                </i>
                                <div class="dropdown-content role-filter">
                                        <h3>Sort by role:</h3>
                                        <a href="?sort=client" >Client</a>
                                        <a href="?sort=agent" >Agent</a>
                                        <a href="?sort=admin" >Admin</a>
                                </div>
                            </div>

                        </th>
                </tr>
            </thead>
            <tbody class="user-table-body">
                <?php foreach ($clients as $client) :?>
                <tr class="user-entry">
                    <td class="user-info">
                        <img class="user-photo" src="assets/images/person.png" alt="user">
                        <div class="user-name">
                            <p><?php echo $client->displayName?></p>
                            <p><?php echo $client->username?></p>
                        </div>
                    </td>
                    <td>
                        <a href="mailto:<?php echo $client->email?>" ><?php echo $client->email?></a>
                    </td>
                    <td>
                        <p class="role <?php echo $client->type?>"><?php echo ucfirst($client->type)?></p>
                    </td>
                    <td>
                        <p><?php
                            $dateTime = date_create("@".$client->createdAt);
                            $dateTime->setTimezone(new DateTimeZone("Europe/Lisbon"));
                            echo $dateTime->format("H:i d/m/o");
                        ?></p>
                    </td>
                    <td>
                        <i class="ri-edit-line icon" onclick="makeEditModal('<?php echo $client->username?>')"></i>
                    </td>
                    <td>
                        <i class="ri-delete-bin-line icon delete" onclick="makeDeleteModal('<?php echo $client->username?>')")></i>
                    </td>
                </tr>
                <?php endforeach;?>

                
            </tbody>
        </table>
    <?php endif;?>
</main>
    
</body>
</html>
