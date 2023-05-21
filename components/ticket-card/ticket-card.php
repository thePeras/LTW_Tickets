<?php
require_once "database/tickets.db.php";
require_once "utils/datetime.php";


function ticketCard(Ticket $ticket)
{
    $id          = $ticket->id;
    $title       = htmlspecialchars($ticket->title);
    $description = htmlspecialchars($ticket->description);
    $status      = htmlspecialchars($ticket->status);
    $hashtags    = htmlspecialchars($ticket->hashtags);

    $hashtags = explode(",", $hashtags);

    $tags = '';
    foreach ($hashtags as $hashtag) {
        $tags .= "<span class='tag'>$hashtag</span>";
    }
    ?>        
        <div class="ticket-card" onclick="location.href = '/ticket?id=<?php echo $id?>'">
            <h3>#<?php echo $id?> - <?php echo $title?></h3>
            <h5><?php echo time_ago($ticket->createdAt)?></h5>
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
