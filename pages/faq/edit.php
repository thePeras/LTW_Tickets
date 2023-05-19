<?php

require_once 'components/layout/layout.php';
require_once 'utils/session.php';
require_once 'utils/roles.php';
require_once 'utils/logger.php';
require_once 'database/database.php';

$db = get_database();

$session = is_session_valid($db);

if ($session === null) {
    header("Location /login");
    exit();
}

if (is_current_user_agent($db) === false) {
    header("Location /");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //TODO: csrf
    if (isset($_POST["title"]) === false || isset($_POST["content"]) === false
        || isset($_POST["id"]) === false
    ) {
        header("Location: /faq", false, 400);
        exit();
    }

    if (modify_faq_entry(
        $_POST["id"],
        $_POST["title"],
        $_POST["content"],
        $session->username,
        $db
    ) === false
    ) {
        log_to_stdout("Error while modifying new faq entry with ".$_POST["id"], "e");
    }

    header("Location: /faq");
    exit();
}

$matches = [];

preg_match("/\/faq\/([[:digit:]]+)\/?/", $_SERVER["REQUEST_URI"], $matches);

if (count($matches) === 0) {
    header("Location: /faq");
    exit();
}

$id = intval($matches[1]);

$faq = get_faq($id, $db);




layout_start();


//TODO csrf
?>
<link rel="stylesheet" type="text/css" href="/css/faq-new.css">
<h1>Edit FAQ</h1>


<hr>

<form method="post" class="create-faq">

    <input type="hidden" name="id" value="<?php echo $faq->id?>">
    <label for="title">
        <p>Title:</p>
    </label>
    <input type="text" name="title" required placeholder="Insert your title..." value="<?php echo $faq->title?>">



    <label for="content">
        <p>Content:</p>
    </label>
    <textarea class="content-area" name="content" rows="10" required placeholder="Insert your content..."><?php echo $faq->content?></textarea>

    <br>
    <input type="submit" value="Edit">

</form>

<?php

layout_end();
