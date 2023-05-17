<?php

declare(strict_types=1);


class Comment
{

    public readonly int $id;

    public string $content;

    public string $createdByUser;

    public DateTime $createdAt;

    public int $ticket;


    public function __construct(string $content, int $ticket,
        string $createdByUser, int $_epoch, int $id=0
    ) {
        $this->id            = $id;
        $this->content       = $content;
        $this->createdByUser = $createdByUser;
        $this->createdAt     = new DateTime("@".$_epoch);
        $this->ticket        = $ticket;

    }


}


function insert_new_comment(Comment $comment, PDO $db) : bool
{

    $sql  = "INSERT INTO Comments(content, createdByUser, createdAt, ticket) VALUES (:content, :createdByUser, :createdAt, :ticket)";
    $stmt = $db->prepare($sql);

    $createdAt = $comment->createdAt->getTimestamp();

    $stmt->bindParam(':content', $comment->content, PDO::PARAM_STR);
    $stmt->bindParam(':createdByUser', $comment->createdByUser, PDO::PARAM_STR);
    $stmt->bindParam(':createdAt', $createdAt, PDO::PARAM_INT);
    $stmt->bindParam(':ticket', $comment->ticket, PDO::PARAM_INT);

    return $stmt->execute();

}
