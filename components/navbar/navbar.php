<?php
function navbar() {
    return <<<HTML
        <link rel="stylesheet" type="text/css" href="components/navbar/navbar.css">

        <nav class="sidebar">
            <div class="logo">
                <h1>Tickets Manager</h1>
            </div>
            <ul>
                <li class="active">
                    <a href="#">
                        <i class="ri-ticket-line"></i>
                        Tickets
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="ri-question-line"></i>
                        FAQ
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="ri-building-line"></i>
                        Departments
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="ri-line-chart-line"></i>
                        Analytics
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="ri-group-line"></i>
                        Users
                    </a>
                </li>
            </ul>
            <div class="user">
                <img class="avatar" src="assets/images/person.png" alt="user">
                <div>
                    <h3>Agostinho Amorim</h3>
                    <p>agostinho@gmail.com</p>
                </div>
                <a href="#" class="logout">
                    <i class="ri-logout-box-line"></i>
                </a>
            </div>
        </nav>
    HTML;
}
