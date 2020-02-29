<?php

include ($_SERVER['DOCUMENT_ROOT']) . '/helpers.php';

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
            <?php include($_SERVER['DOCUMENT_ROOT']). '/sidebar.php'; ?>
        </div>
        <div class="col-sm-6 text-left forum-container">
        <!-- Actual content -->
            <h1>Forum</h1>

            <a href="create_thread.php"><button type="button" class="btn btn-primary">Create thread</button></a>
            <br> <br>

            <div class="forum-posts shadow">
                <ul class="list-group list-group-flush ">
                    <?
                    $db = new DB();
                    $threads = $db->getAllThreads();
                    foreach ($threads as $thread) {

                        echo '
                        <li class="forum-post-link list-group-item">
                        Posted by ' . $thread->Author . ' '. time_elapsed_string('@'. $thread->Time) .' - '. $thread->AmountOfReplies .' replies ';
                        if ($thread->Last_Comment != null) {
                            $lastcomment = $db->getCommentFromID($thread->Last_Comment);

                            echo '
                            - Last reply by '. $lastcomment->Author .' '. time_elapsed_string('@'. $lastcomment->Time);
                            ;
                        }
                        echo '
                        <br><a href="thread.php?title='. $thread->Title .'" class="list-group-item forum-post-link">'. $thread->Title .'</a>
                        </li>
                        ';
                    }
                    ?>

                    <!-- EXAMPLE
                    <li class="forum-post-link list-group-item">
                        posted by bobkun - 5 replies - last reply 2 days ago
                        <br><a href="" class="list-group-item forum-post-link">NYXL Week 3 Highlights video</a>
                    </li>
                    <li class="list-group-item forum-post-link">
                        posted by bobkun - 5 replies - last reply 2 days ago
                        <br><a href="" class="list-group-item forum-post-link">I was catching up on the stream and hit pause when ZP looked oddly familiar </a>
                    </li>
                    <li class="list-group-item forum-post-link">
                        posted by bobkun - 5 replies - last reply 2 days ago
                        <br><a href="" class="list-group-item forum-post-link">When Lucio hits hero ban pool.... </a>
                    </li>
                    -->

                </ul>
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