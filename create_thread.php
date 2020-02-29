<?php
session_start();

include ($_SERVER['DOCUMENT_ROOT']) . '/helpers.php';

$loggedin = false;

if (isset($_SESSION["username"])) {
$loggedin = true;
}

$title = $content = "";
$titleErr = $contentErr = $submitErr = "";
$ERROR_EMPTY = "Error: Can not be empty!";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];

    if ($loggedin == false) {
        $submitErr = "You must be logged in to post. Also you just hacked the site didn't you?";
    }

    if (empty($_POST["title"])) {
        $titleErr = $ERROR_EMPTY;
    }

    else {
        $title = test_input($_POST["title"]);

        if (strlen($title) > 200) {
            $titleErr = "Error: Title can not be more than 200 characters!";
        }
    }

    if (empty($_POST["content"])) {
        $contentErr = $ERROR_EMPTY;
    }

    else {
        $content = test_input($_POST["content"]);

        if (strlen($content) > 1000) {
            $contentErr = "Error: Title can not be more than 1000 characters!";
        }
    }

    if ($titleErr == "" && $contentErr == "" && $submitErr == "") {

        $db = new DB();

        if ($db->getThread($title) != null) {
            $titleErr = "Error: thread already exists";
        }

        else {
            if ($db->addThread($_SESSION["username"], $title, $content) != -1) {
                header('Location: thread.php?title=' . urlencode($title));
                die();
            }

            else {
                $submitErr = "Error, unknown database error, please try again";
            }
        }

    }

}


?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forum</title>

    <link rel="stylesheet" href="css/mystyle.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</head>
<body>

<?php include($_SERVER['DOCUMENT_ROOT']).'/navbar.php'; ?>



<div class="container-fluid text-center">
    <div class="row content">
        <div class="col-sm-2 sidenav shadow">
            <?php include($_SERVER['DOCUMENT_ROOT']).'/sidebar.php'; ?>
        </div>
        <div class="col-sm-6 text-left">
            <!-- Actual content -->

            <div id="create-thread" class="bg-light shadow">
                <h1>Create thread</h1>
                <p>Write your post.
                    <br><span class="text-info">Tip: Use [b]text[/b] for <b>bold</b>, and [i]text[/i] for <i>italics</i>. Use [img]URL[/img] for images.</span></p>

                <form action="" method="post">
                    <div class="form-group">
                        <input class="form-control" type="text" name="title" placeholder="Title">
                        <p class="text-danger"><b> <?= $titleErr ?> </b></p>
                        <br>
                        <textarea class="form-control" rows="12" id="comment" name="content"></textarea>
                        <p class="text-danger"><b> <?= $contentErr ?> </b></p>
                        <? if ($loggedin) {
                            echo '<button type="submit" class="btn btn-primary">Submit</button>';
                        }
                        else {
                            echo '<p class="text-danger">You must be logged in to create threads!';
                        } ?>
                        <p class="text-danger"><b> <?= $submitErr ?> </b></p>
                    </div>
                </form>
            </div>

        </div>

        <div class="col-sm-4">
            <!-- Right side is just empty -->
        </div>

    </div>
</div>





</div>


</body>
</html>