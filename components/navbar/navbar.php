<?php
require_once __DIR__.'/../../utils/session.php';
require_once __DIR__.'/../../utils/roles.php';


require_once __DIR__.'/../../database/client.db.php';


function get_navbar_user(?Client $client, PDO $db) : string
{
    if ($client === null) {
        return '';
    }

    $imageSrc = $client->image;

    return <<<HTML
        <div class="user" onclick="location.href='/profile'">
                <img class="avatar" src=$imageSrc alt="user">
                <div>
                    <h3>$client->displayName</h3>
                    <p>@$client->username</p>
                </div>
            <a href="/logout" class="logout">
                <i class="ri-logout-box-line"></i>
            </a>
        </div>
    HTML;

}


function get_login_button() : string
{
    return <<<HTML
        <a href="/login" class="login">
            <i class="ri-login-box-line"></i>
            Login
        </a>
    HTML;

}


function navbar(PDO $db)
{

    $isActive = function (string $path, string $current) : string {
        if ($path === $current) {
            return 'true';
        } else {
            return 'false';
        };
    };

    $session = is_session_valid($db);
    $client  = null;
    if ($session !== null) {
        $client = get_user($session->username, $db);
        if (file_exists(__DIR__.'/../../'.$client->image) === false) {
            set_default_client_image($client, $db);
            $client = get_user($client->username, $db);
        }
    }
    ?>
        <link rel="stylesheet" type="text/css" href="/components/navbar/navbar.css">

        <input type="checkbox" id="navbar-checkbox" checked> 
        <label for="navbar-checkbox">
            <i class="ri-menu-line" id="navbar-open"></i>
            <i class="ri-close-line" id="navbar-close"></i>
        </label>

        <nav class="sidebar sticky">
            <div class="inital-sidebar">
                <div class="logo">
                    <h1>Tickets Manager</h1>
                </div>
                <ul>
                    <li <?php
                    if (str_contains($_SERVER['REQUEST_URI'], "/tickets") === true) {
                        echo 'class="active"';
                    }
                    ?>>
                        <a href="#">
                            <i class="ri-ticket-line"></i>
                            Tickets
                        </a>
                    </li>
                    <li>
                        <a href="/faq">
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
                    <?php if (is_current_user_admin($db) === true) :?>
                    <li <?php
                    if (str_contains($_SERVER['REQUEST_URI'], "/admin") === true) {
                        echo 'class="active"';
                    }
                    ?>>
                        <a href="/admin">
                            <i class="ri-admin-line"></i>
                            Admin settings
                        </a>
                    </li>
                    <?php endif;?>
                </ul>
            </div>
            <?php
            if ($session !== null) {
                echo get_navbar_user($client, $db);
            } else {
                echo get_login_button();
            }
            ?>
        </nav>
    <?php

}
