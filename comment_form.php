<?php

$content = "";
$contentErr = $submitErr = "";
$ERROR_EMPTY = "Error: Can not be empty!";

$loggedin = false;

if (isset($_SESSION["username"])) {
    $loggedin = true;
}

if ($loggedin == false) {
    $submitErr = "You must be logged in to post. Also you just hacked the site didn't you?";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (empty($_POST["content"])) {
        $contentErr = $ERROR_EMPTY;
    }

    else {
        $content = test_input($_POST["content"]);

        if (strlen($content) > 1000) {
            $contentErr = "Error: Title can not be more than 1000 characters!";
        }
    }

    if ($contentErr == "" && $submitErr == "") {

        $db = new DB();
        $time = time();
        $commentID = $db->addComment($thread->Title, $_SESSION["username"], $content, $time);

        if ($commentID != -1) {
            $db->updateThread($thread, $time, $commentID);
            $content = "";
            header('Location: thread.php?title=' . urlencode($title));
            die();
        } else {
            $submitErr = "Error, unknown database error, please try again";
        }

    }

}
?>

