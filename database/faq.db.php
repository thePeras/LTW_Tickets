<?php

declare(strict_types=1);

class FAQ
{

    public readonly int $id;

    public readonly string $createdByUser;

    public readonly string $title;

    public readonly string $content;


    public function __construct(int $_id, string $_createdByUser,
        string $_title, string $_content
    ) {
        $this->id            = $_id;
        $this->createdByUser = $_createdByUser;
        $this->title         = $_title;
        $this->content       = $_content;

    }


}


function get_FAQs(int $limit, int $offset, PDO $db) : array
{
    $sql = "SELECT * FROM FAQs LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

    $stmt->execute();

    $result = $stmt->fetchAll();

    return array_map(
        function (array $a) : FAQ {
            return new FAQ($a["id"], $a["createdByUser"], $a["title"], $a["content"]);
        },
        $result
    );

}


function create_faq_entry(string $title, string $content, string $user, PDO $db) : bool
{
    $sql = "INSERT INTO FAQs (createdByUser, title, content) VALUES (:user, :title, :content)";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(":user", $user);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":content", $content);

    return $stmt->execute();

}
