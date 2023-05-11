<?php
require_once __DIR__.'/../../utils/render_utils.php';
require_once __DIR__.'/../../utils/session.php';

require_once __DIR__.'/../../database/client.db.php';


function get_navbar_user(?Client $client) : string
{
    if ($client === null) {
        return '';
    }

    return  <<<HTML
        <div class="user">
            <img class="avatar" src="assets/images/person.png" alt="user">
            <div>
                <h3>$client->displayName</h3>
                <p>$client->email</p>
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
    global $ifHtml;
    $session = is_session_valid($db);
    $client  = null;
    if ($session !== null) {
        $client = get_user($session->username, $db);
    }

    $path = $_SERVER['REQUEST_URI'];
    $path = explode('?', $path)[0];
    $path = explode('/', $path);
    $path = $path[(count($path) - 1)];

    return <<<HTML
        <link rel="stylesheet" type="text/css" href="components/navbar/navbar.css">

        <nav class="sidebar">
            <div class="logo">
                <h1>Tickets Manager</h1>
            </div>
            <ul>
                <li data-active="true">
                    <a href="/">
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
                    <a href="/departments">
                        <i class="ri-building-line"></i>
                        Departments
                    </a>
                </li>
                <li>
                    <a href="/analytics">
                        <i class="ri-line-chart-line"></i>
                        Analytics
                    </a>
                </li>
                <li>
                    <a href="/users">
                        <i class="ri-group-line"></i>
                        Users
                    </a>
                </li>
            </ul>
            {$ifHtml(($session !== null), get_navbar_user($client), get_login_button())}
        </nav>
    HTML;

}
