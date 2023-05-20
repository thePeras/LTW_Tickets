<?php
require_once "database/tickets.db.php";


function ticketCardHardcoded()
{
    return <<<HTML
        <link rel="stylesheet" type="text/css" href="components/ticket-card/ticket-card.css">
        
        <div class="ticket-card" data-id="1">
            <h3>Cannot access the system</h3>
            <h5>3h ago</h5>
            <p>Life seasons open have. Air have of. Lights fill after let third darkness replenish fruitful let. Wherein set image. Creepeth said above gathered bring</p>
            <footer>
                <tags>
                    <span class="tag urgent">Urgent</span>
                    <span class="tag">a thing</span>
                </tags>
                <comments>
                    <span class="ri-chat-1-line"></span>
                    <span>3</span>
                </comments>
            </footer>
        </div>

        <script src="js/ticket-card.js"></script>
    HTML;

}


function ticketCard(Ticket $ticket)
{
    $id          = $ticket->id;
    $title       = htmlspecialchars($ticket->title);
    $description = htmlspecialchars($ticket->description);
    $status      = htmlspecialchars($ticket->status);
    $timeAgo     = $ticket->getTimeAgo();
    $hashtags    = htmlspecialchars($ticket->hashtags);

    $hashtags = explode(",", $hashtags);

    $tags = '';
    foreach ($hashtags as $hashtag) {
        $tags .= "<span class='tag'>$hashtag</span>";
    }

    return <<<HTML
        <link rel="stylesheet" type="text/css" href="components/ticket-card/ticket-card.css">
        
        <div class="ticket-card" data-id="$id">
            <h3>#$id - $title</h3>
            <h5>$timeAgo</h5>
            <p>$description</p>
            <footer>
                <tags>
                    <span class="tag">$status</span>
                    $tags
                </tags>
                <!---
                <comments>
                    <span class="ri-chat-1-line"></span>
                    <span>3</span>
                </comments>
                -->
            </footer>
        </div>

        <script src="js/ticket-card.js"></script>
    HTML;

}
