<?php
    require_once 'components/navbar/navbar.php';
    require_once 'database/database.php';
    require_once 'utils/action_ticket.php';
    require_once 'utils/datetime.php';

    $db = get_database();

    $error   = null;
    $success = null;

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

    $action = ($_POST["action"] ?? "");
    switch ($action) {
    case "create":
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
        break;
    case "close": //TODO: set changeStatus
        $ticketId = ($_POST["ticketId"] ?? "");
        if ($ticketId === "") {
            $error = "Invalid ticket";
            break;
        }

        $ticket = get_ticket($ticketId, $db);
        if ($ticket !== null) {
            if ($ticket->status === "closed") {
                $error = "Ticket is already closed";
                break;
            } else {
                $error = close_ticket($ticketId, $db);
                if ($error === null) {
                    $success = "Ticket closed successfully";
                }
            }
        } else {
            $error = "Ticket not found";
            break;
        }
        break;
    case "assign":
        $ticketId = ($_POST["ticketId"] ?? null);
        $ticketId = intval($ticketId);
        $user     = ($_POST["user"] ?? null);
        $error    = assign_ticket($ticketId, $user, $db);
        if ($error === null) {
            $success = "Ticket assigned successfully";
        }
        break;
    case "unassign":
        $ticketId = ($_POST["ticketId"] ?? null);
        $ticketId = intval($ticketId);
        $error    = unassign_ticket($ticketId, $db);
        if ($error === null) {
            $success = "Ticket unassigned";
        }
        break;
    case "changeDepartment":
        $ticketId   = ($_POST["ticketId"] ?? "");
        $department = ($_POST["department"] ?? "");
        $error      = change_department($ticketId, $department, $db);
        if ($error !== null) {
            http_response_code(400);
            echo $error;
            exit();
        }

        http_response_code(200);
        echo "Department changed successfully";
        exit();
        break;
    }
}

    $id = $_GET['id'];
if ($id === null) {
    header('Location: /tickets');
}

    $ticket = get_ticket($id, $db);
if ($ticket === null) {
    header('Location: /tickets');
}

    $author = get_ticket_author($ticket->createdByUser, $db);

    $comments = get_comments($id, $db);
    $changes  = get_changes($id, $db);
    $all      = array_merge($comments, $changes);
    usort( //TODO: not working
        $all,
        function ($a, $b) {
            return ($a->timestamp->getTimestamp() - $b->timestamp->getTimestamp());
        }
    );

    $departments = get_departments(0, 30, $db, false);

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

    //TODO: SAFE??
    if ($error !== null) {
        echo "<script>snackbar('$error', 'error')</script>";
    }

    if ($success !== null) {
        echo "<script>snackbar('$success', 'success')</script>";
    }
    ?>
    <input type="hidden" id="ticketId" value="<?php echo $ticket->id ?>">
    <main class="ticket-page">
        <div>
            <h1><?php echo "$ticket->title #$ticket->id"?></h1>
            <ul id="buttons">
                <!-- TODO: Change to OPEN with ticket is closed-->
                <form method="post" action="ticket?id=<?php echo $ticket->id ?>">
                    <input type="hidden" name="action" value="close">
                    <input type="hidden" name="ticketId" value="<?php echo $ticket->id ?>">
                    <li><button type = "button" onClick="closeTicket(event,this)"> 
                        <i class="ri-archive-line"></i>
                        Close ticket 
                    </button></li>
                </form>
            </ul>

            <!-- Ticket description -->
            <div class="ticket-comment">
                <div class="user-comment">
                    <div class="user">
                        <img class="avatar" src="<?php echo $author->image ?>" alt="user">
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

            <?php foreach ($all as $item) : ?>
                <!-- Change --> 
                <?php if (($item instanceof AssignedChange) === true) : ?> 
                        <div class="event">
                            <img class="avatar" src="
                                <?php echo $item->user->image ?>
                            " alt="user">
                            <div>
                                <h4>
                                    <?php echo $item->user->displayName?>
                                </h4>
                                <?php if ($item->agent->username === "") : ?>
                                    <p>unassign this ticket</p>
                                    <p>
                                        <?php echo time_ago($item->timestamp) ?>
                                    </p>
                                <?php else : ?>
                                <p>assign this ticket to 
                                    <b><?php echo $item->agent->displayName ?></b>
                                </p>
                                <p>
                                    <?php echo time_ago($item->timestamp) ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                <?php ; elseif (($item instanceof StatusChange) === true) :?> 
                    <div class="event">
                        <img class="avatar" src="
                            <?php echo $item->user->image ?>
                        " alt="user">
                        <div>
                            <h4>
                                <?php echo $item->user->displayName?>
                            </h4>
                            <p>udpate status to 
                                <b><?php echo $item->status ?></b>
                            </p>
                            <p>
                                <?php echo time_ago($item->timestamp) ?>
                            </p>
                        </div>
                    </div>
                <!-- Comment --> 
                <?php ; elseif (($item instanceof Comment) === true) :?> 
                    <div class="ticket-comment">
                        <div class="user-comment">
                            <div class="user">
                                <img class="avatar" src="
                                    <?php echo $item->createdByUser->image ?>
                                " alt="user" />
                                <h3><?php echo $item->createdByUser->displayName ?></h3>
                                <p>
                                    <?php echo time_ago($item->timestamp) ?>
                                </p>
                            </div>
                            <p>
                                <?php echo $item->content ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <form method="post" action="ticket?id=<?php echo $ticket->id ?>">
                <div class="comment-box top-line">
                    <h3>New comment</h3>
                    <textarea name="content" cols="30" rows="10" placeholder="Write your comment"></textarea>
                    <input type="hidden" name="ticketId" value="<?php echo $ticket->id ?>">
                    <input type="hidden" name="action" value="create">
                    <input type="submit" class="primary" value="Send">
                </div>
            </form>
        </div>

        <div class="action-panel">
            <div class="side-card">
                <div>
                    <h4 class="task-label">Assignee</h4>
                    <?php if ($ticket->assignee->username !== "") : ?>
                        <div class="user">
                            <div>
                                <img class="avatar" src="<?php echo $ticket->assignee->image ?>" alt="user">
                                <?php echo $ticket->assignee->displayName ?>
                            </div>
                            <?php if ($loggedUser->type === "admin" || $loggedUser->type === "agent") : ?>
                                <form method="POST" action="
                                    <?php echo "ticket?id=$ticket->id" ?>
                                ">
                                    <input type="hidden" name="action" value="unassign">
                                    <input type="hidden" name="ticketId" value="<?php echo $ticket->id ?>">
                                    <i class="ri-close-line" onclick="removeAssignee(event, this)"></i>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php else : ?>
                    <p onclick="makeUserAssignModal(
                        <?php
                            echo "'$loggedUser->type'";
                        ?>
                    )">
                        <i class="ri-account-circle-line"></i>
                        Unassigned
                    </p>
                    <?php endif; ?>
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
                    <select name="departments" id="departmentSelect">
                        <option value="">No department</option> 
                        <?php foreach ($departments as $department) : ?>
                            <option value="<?php echo $department->name ?>">
                                <?php echo $department->name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
