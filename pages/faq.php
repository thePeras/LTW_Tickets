<?php

require_once 'components/layout/layout.php';
require_once 'utils/routing.php';

handle_page_route("/faq/new", __DIR__."/faq/new.php");

layout_start();

$limit  = min(intval(($_GET["limit"] ?? 10)), 20);
$offset = intval(($_GET["offset"] ?? 0))



?>
    <link rel="stylesheet" type="text/css" href="/css/faq.css">
    <script src="js/faq.js"></script>
    

    <h1>FAQ</h1>
    <p>Frequently Asked Questions</p>
    <div>
        <h4>Have Questions? We're here to help.</h4>
        <input type="text" placeholder="Search">
    </div>
    <div>
        <div class="faq-question">
            <header>
                <h2>#1 - How do I get started?</h2>
                <i class="ri-add-circle-line"></i>
            </header>
            <div class="content">
                <p>Click on the "Register" button on the top right of the page. Fill out the form and click "Register".</p>
            </div>
        </div>

        <div class="faq-question">
            <header>
                <h2>#2 - How do I get started?</h2>
                <i class="ri-add-circle-line"></i>
            </header>
            <div class="content">
                <p>Click on the "Register" button on the top right of the page. Fill out the form and click "Register".</p>
            </div>
        </div>
    </div>

    <div class="faq-footer">
        <h2>Still have questions?</h2>
        <p>Don't worry. Create an issue and we'll get back to you as soon as possible.</p>
        <button type="button" class="primary" onclick="location.href = '/faq/new'">Create an Issue</button>
    </div>


<?php


layout_end();
