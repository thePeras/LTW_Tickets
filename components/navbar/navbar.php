<?php
function navbar() {
    return <<<HTML
        <link rel="stylesheet" type="text/css" href="components/navbar/navbar.css">

        <nav class="sidebar">
            <div class="logo">
                <h1>Tickets Manager</h1>
            </div>
            <ul>
                <li class="active"><a href="#">Tickets</a></li>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Departments</a></li>
                <li><a href="#">Analytics</a></li>
                <li><a href="#">Users</a></li>
            </ul>
            <div class="user">
                <img class="avatar" src="assets/images/person.png" alt="user">
                <div>
                    <h3>Agostinho Amorim</h3>
                    <p>agostinho@gmail.com</p>
                </div>
                <img class="logout" src="assets/images/logout.png" alt="logout" title="Logout">
            </div>
        </nav>
    HTML;
}?>
