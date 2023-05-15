<?php

require 'components/layout/layout.php';

layout_start();

echo <<<HTML
    <link rel="stylesheet" type="text/css" href="css/faq.css">

    <h1>FAQ</h1>
    <p>Frequently Asked Questions</p>
    <div>
        <h4>Have Questions? We're here to help.</h4>
        <input type="text" placeholder="Search">
    </div>
    <div>
        <div class="faq-question">
            <header>
                <h2>How do I get started?</h2>
                <i class="ri-add-circle-line"></i>
            </header>
            <div class="content">
                <p>Click on the "Register" button on the top right of the page. Fill out the form and click "Register".</p>
            </div>
        </div>

        <div class="faq-question">
            <header>
                <h2>How do I get started?</h2>
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
        <button type="button" class="primary">Create an Issue</button>
    </div>

    <script>
        const faqQuestions = document.querySelectorAll('.faq-question');

        faqQuestions.forEach((faqQuestion) => {
            faqQuestion.addEventListener('click', (e) => {
                // Clicking in the content do nothing
                if (e.target.classList.contains('content') || e.target.parentElement.classList.contains('content')) {
                    return;
                }

                //ri-add-circle-line: closed status
                //ri-close-circle-line: open status
                faqQuestion.querySelector('i').classList.toggle('ri-add-circle-line');
                faqQuestion.querySelector('i').classList.toggle('ri-close-circle-line');
                faqQuestion.classList.toggle('active');
            });
        });
    </script>
HTML;


layout_end();
