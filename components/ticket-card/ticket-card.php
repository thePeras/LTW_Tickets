<?php
require_once "database/tickets.db.php";


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
    ?>        
        <div class="ticket-card" onclick="location.href = '/ticket/<?php echo $id?>'">
            <h3>#<?php echo $id?> - <?php echo $title?></h3>
            <h5><?php echo $timeAgo?></h5>
            <p><?php echo $description?></p>
            <footer>
                <div>
                    <span class="tag"><?php echo $status?></span>
                    <?php echo $tags?>
                </div>
                <!---
                <comments>
                    <span class="ri-chat-1-line"></span>
                    <span>3</span>
                </comments>
                -->
            </footer>
        </div>

    <?php

}
