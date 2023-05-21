<?php
require_once "database/tickets.db.php";
require_once "utils/datetime.php";


function ticketCard(Ticket $ticket)
{
    $id          = $ticket->id;
    $title       = htmlspecialchars($ticket->title);
    $description = htmlspecialchars($ticket->description);
    $status      = htmlspecialchars($ticket->status->status);
    $statusColor = htmlspecialchars($ticket->status->color);
    $statusBackgroundColor = htmlspecialchars($ticket->status->backgroundColor);

    $timeAgo = time_ago($ticket->createdAt);

    $tags = '';
    foreach ($ticket->labels as $label) {
        $labelName            = htmlspecialchars($label->label);
        $labelColor           = htmlspecialchars($label->color);
        $labelBackgroundColor = htmlspecialchars($label->backgroundColor);

        $tags .= "<span class='tag' style='color:$labelColor; background-color: $labelBackgroundColor'>$labelName</span>";
    }
    ?>        
        <div class="ticket-card" onclick="location.href = '/ticket?id=<?php echo $id?>'">
            <h3>#<?php echo $id?> - <?php echo $title?></h3>
            <h5><?php echo time_ago($ticket->createdAt)?></h5>
            <p><?php echo $description?></p>
            <footer>
                <div>
                    <span class="tag" style="color: <?php echo $statusColor?>; 
                        background-color: <?php echo $statusBackgroundColor?>;">
                        <?php echo $status?>
                    </span>
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
