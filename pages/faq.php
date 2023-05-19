<?php

require_once 'components/layout/layout.php';
require_once 'utils/routing.php';
require_once 'utils/roles.php';
require_once 'database/database.php';
require_once 'database/faq.db.php';



$db = get_database();

handle_page_route("/faq/new", __DIR__."/faq/new.php");

layout_start();

$limit  = min(intval(($_GET["limit"] ?? 10)), 20);
$offset = intval(($_GET["offset"] ?? 0));

$faqs = get_FAQs($limit, $offset, $db);

?>
    <link rel="stylesheet" type="text/css" href="/css/faq.css">
    <script src="js/faq.js"></script>
    

    <h1>FAQ</h1>
    <p>Frequently Asked Questions</p>
    <div class="top-content">
        <div class="search">
            <h4>Have Questions? We're here to help.</h4>
            <input type="text" placeholder="Search">
        </div>
        <?php if (is_current_user_agent($db) === true) :?>
            <button  type="button" class="create-faq primary" onclick="location.href= '/faq/new'">Create new...</button>
        <?php endif;?>
    </div>
    <div>
        <?php foreach ($faqs as $faq) :
            $user    = get_user($faq->createdByUser, $db);
            $content = explode("\n", $faq->content)?>
        <div class="faq-question">
            <header>
                <h2>#<?php echo $faq->id?> - <?php echo $faq->title?></h2>
                <i class="ri-add-circle-line"></i>
            </header>
            <div class="content">
                <?php foreach ($content as $paragraph) :?>
                <p><?php echo $paragraph?></p>
                <?php endforeach;?>
                <div class="created-by">
                    <p>By:</p>
                    <img class="avatar" src="/assets/images/person.png" alt="user">
                    <p class="display-name"><?php echo $user->displayName?></p>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>

    <div class="faq-footer">
        <h2>Still have questions?</h2>
        <p>Don't worry. Create an issue and we'll get back to you as soon as possible.</p>
        <button type="button" class="primary">Create an Issue</button>
    </div>


<?php


layout_end();
