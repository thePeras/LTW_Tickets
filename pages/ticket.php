<?php
    require 'components/navbar/navbar.php';

    $id = $_GET['id'];
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
</head>
<body>
    <?php
        echo navbar();
    ?>
    <main class="ticket-page">
        <div>
            <h1>Issue about log in #2</h1>
            <ul id="buttons">
                <li><button type = "button"> Status </button></li>
                <li><button type = "button" class = "active"> Close </button></li>
            </ul>

            <div class="user-comment">
                <div class="user">
                    <img class="avatar" src="assets/images/person.png" alt="user">
                    <h3>Agostinho Amorim</h3>
                    <p>15 min ago</p>
                </div>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Quisquam, voluptatum. Quisquam, voluptatum. Quisquam, voluptatum
                    Fe fugiat, quibusdam, voluptatum, quod quia quas voluptates
                </p>
            </div>

            <div class="event">
                <img class="avatar" src="assets/images/person.png" alt="user">
                <div>
                    <h4>Agostinho Amorim</h4>
                    <p>close this</p>
                    <p>15 min ago</p>
                </div>
            </div>

            <div class="user-comment">
                <div class="user">
                    <img class="avatar" src="assets/images/person.png" alt="user">
                    <h3>Agostinho Amorim</h3>
                    <p>15 min ago</p>
                </div>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Quisquam, voluptatum. Quisquam, voluptatum. Quisquam, voluptatum
                    Fe fugiat, quibusdam, voluptatum, quod quia quas voluptates
                </p>
            </div>

            <div class="event">
                <img class="avatar" src="assets/images/person.png" alt="user">
                <div>
                    <h4>Agostinho Amorim</h4>
                    <p>close this</p>
                    <p>15 min ago</p>
                </div>
            </div>

            <div class="comment-box">
                <h3>New comment</h3>
                <textarea name="comment" id="comment" cols="30" rows="10" placeholder="Write your comment"></textarea>
                <button type="button" class="primary">Submit</button>
            </div>
        </div>
        <div class="action-panel">
            <div class="side-card">
                <div>
                    <h4 class="task-label">Assignee</h4>
                    <p>
                        <i class="ri-account-circle-line"></i>
                        Unassigned
                    </p>
                </div>

                <div>
                    <h4 class="task-label">Team</h4>
                    <p>
                        <i class="ri-group-2-line"></i> 
                        Unassigned
                    </p>
                </div>
            </div>
            <div class="side-card">
                <h4 class="task-label">Labels</h4>
                <div>
                    <p>
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
