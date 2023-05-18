<?php
    require_once 'components/navbar/navbar.php';
    require_once 'database/database.php';
    require_once 'utils/action_ticket.php';
    require_once 'utils/datetime.php';

    $db = get_database();

    $error = null;

    $session = is_session_valid($db);

    $loggedUser = null;
if ($session !== null) {
    $loggedUser = get_user($session->username, $db);
}


    // Adding a new comment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (is_session_valid($db) === null) {
        header('Location: /login');
        exit();
    }

    $content = $_POST['content'];
    $ticket  = $_POST['ticketId'];

    if ($content === null || $ticket === null) {
        $error = 'Invalid comment';
    }

    if ($content === '') {
        $error = 'Comment cannot be empty';
    }

    if ($error === null) {
        $created = create_comment($content, $ticket, $db);
        if ($created === false) {
            $error = 'Error creating comment';
        }
    }
}

    $id     = $_GET['id'];
    $ticket = get_ticket($id, $db);
if ($ticket === null) {
    header('Location: /tickets');
}

    $author = get_ticket_author($ticket->createdByUser, $db);

    $comments = get_comments($id, $db);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #2</title>
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/remixicon.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/ticket.css">
    <link rel="stylesheet" href="css/modal.css">
    <script src="js/ticket-page.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/snackbar.js"></script>
</head>
<body>
    <?php
        $db = get_database();
        echo navbar($db);
    ?>
    <main class="ticket-page">
        <div>
            <h1><?php echo "$ticket->title #$ticket->id"?></h1>
            <ul id="buttons">
                <li><button type = "button"> Status </button></li>
                <li><button type = "button" class = "active"> Close </button></li>
            </ul>

            <!-- Ticket description -->
            <div class="ticket-comment">
                <div class="user-comment">
                    <div class="user">
                        <img class="avatar" src="assets/images/person.png" alt="user">
                        <h3>
                            <?php echo $author->displayName ?>
                        </h3>
                        <p>
                            <?php echo time_ago($ticket->createdAt) ?>
                        </p>
                    </div>
                    <p>
                        <?php echo $ticket->description ?>
                    </p>
                </div>
            </div>

            <!-- Comments -->
            <?php foreach ($comments as $comment) : ?>
                <div class="ticket-comment">
                    <div class="user-comment">
                        <div class="user">
                            <img class="avatar" src="assets/images/person.png" alt="user" />
                            <h3><?php echo $comment["displayName"] ?></h3>
                            <p>
                                <?php echo time_ago($comment["comment"]->createdAt) ?>
                            </p>
                        </div>
                        <p>
                            <?php echo $comment["comment"]->content ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>

            <!--
            <div class="event">
                <img class="avatar" src="assets/images/person.png" alt="user">
                <div>
                    <h4>Agostinho Amorim</h4>
                    <p>close this</p>
                    <p>15 min ago</p>
                </div>
            </div>
            -->

            <form method="post" action="ticket?id=<?php echo $ticket->id ?>">
                <div class="comment-box top-line">
                    <h3>New comment</h3>
                    <textarea name="content" cols="30" rows="10" placeholder="Write your comment"></textarea>
                    <input type="hidden" name="ticketId" value="<?php echo $ticket->id ?>">
                    <input type="submit" class="primary" value="Send">
                </div>
            </form>
        </div>

        <div class="action-panel">
            <div class="side-card">
                <div>
                    <h4 class="task-label">Assignee</h4>
                    <p onclick="makeUserAssignModal(
                        <?php
                            echo "'$loggedUser->type'";
                        ?>
                    )">
                        <i class="ri-account-circle-line"></i>
                        Unassigned
                    </p>
                </div>
            </div>
            <div class="side-card">
                <h4 class="task-label">Labels</h4>
                <div>
                    <p onclick="makeLabelsModal(
                        <?php
                            echo "'$loggedUser->type'";
                        ?> 
                    )">
                        <i class="ri-price-tag-3-line"></i>
                        No labels assigned
                    </p>
                </div>
            </div>
            <div class="side-card">
                <h4 class="task-label">Department</h4>
                <div>
                    <select name="departments">
                        <option value="1">Department 1</option>
                        <option value="2">Department 2</option>
                        <option value="3">Department 3</option>
                        <option value="3">Department 4</option>
                    </select>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
