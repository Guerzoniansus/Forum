<?php
session_start();
include ($_SERVER['DOCUMENT_ROOT']) . '/helpers.php';


$thread = null;
$db = new DB();



    if (isset($_GET["title"])) {
        $title = test_input(str_replace("+", " ", $_GET["title"]));

        $thread = $db->getThread($title);
        $thread->Content = bbCode($thread->Content);

    }

include ($_SERVER['DOCUMENT_ROOT']) . '/comment_form.php';


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

            <div id="thread" class="bg-light shadow border-bottom">
                <h1 class="thread-title"><?= $thread->Title ?></h1>
                <p class="thread-info">Submitted by <?= $thread->Author ?>     -     <?= time_elapsed_string('@'. $thread->Time) ?></p>
                <p class="thread-content border"><?= nl2br($thread->Content) ?> </p>
            </div>

            <!-- example
            <div class="comment bg-light">
                <p class="comment-info">Submitted by bob - 2020-02-28</p>
                <p class="comment-content border">hello yo yo</p>
            </div>
            -->

            <?
            $allcomments = $db->getAllComments($thread->Title);

            foreach ($allcomments as $comment) {
                echo '
                <div class="comment bg-light">
                <p class="comment-info">Submitted by '. $comment->Author .' - '. time_elapsed_string('@'. $comment->Time) .'</p>
                <p class="comment-content border">'. nl2br(bbCode($comment->Content)) .'</p>
                </div>
                ';
            }
            ?>

            <? if (isset($_SESSION["username"])) {
                echo '
                <div id="comment-form" class="bg-light shadow">
                    <h2 class="thread-title">Comment</h2>
                
                    <form action="" method="post">
                        <div class="form-group">
                            <textarea class="form-control" rows="12" id="comment" name="content"></textarea>
                            <p class="text-danger"><b> <?= $contentErr ?> </b></p>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <p class="text-danger"><b> <?= $submitErr ?> </b></p>
                        </div>
                    </form>
                </div>
                ';
                }
            ?>

        </div>

        <div class="col-sm-4">
            <!-- Right side is just empty -->
        </div>

    </div>
</div>





</div>


</body>
</html>


