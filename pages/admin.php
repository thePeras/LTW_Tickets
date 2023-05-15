<?php
require_once __DIR__.'/../database/database.php';
require_once __DIR__.'/../utils/roles.php';
require_once __DIR__.'/../utils/session.php';
require_once __DIR__.'/../components/navbar/navbar.php';
require_once __DIR__.'/../database/client.db.php';


$db = get_database();

if (is_session_valid($db) === false) {
    header("Location: /login");
    exit();
}

if (is_current_user_admin($db) === false) {
    header("Location: /");
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
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/remixicon.css">
</head>
<body>

<?php
    navbar($db);

?>
<main>
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
            $clients = get_clients($limit, $offset, $db);
        ?>
        
        <table class="user-table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox">
                    </th>
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
                            <i class="ri-filter-line icon"></i>

                        </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client) :?>
                <tr class="user-entry">
                    <td>
                        <input type="checkbox">
                    </td>
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
                            $dateTime = new DateTime("@".$client->createdAt);
                            echo date_format($dateTime, "H:i d/m/o");
                        ?></p>
                    </td>
                    <td>
                        <i class="ri-edit-line icon"></i>
                    </td>
                    <td>
                        <i class="ri-delete-bin-line icon" style="color: var(--delete-color)"></i>
                    </td>
                </tr>
                <?php endforeach;?>

                
            </tbody>
        </table>
    <?php endif;?>
</main>
    
</body>
</html>
