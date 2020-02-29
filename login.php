<?php
include ($_SERVER['DOCUMENT_ROOT']) . '/helpers.php';

$registered = false;

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (isset($_GET["register"])) {

        if ($_GET["register"] == "ok") {
            $registered = true;
        }
    }

}

$userErr = $passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validation
    // If found user, start session, create cookie, redirect to index

    $username = $password = "";
    $err = false;

    if (empty($_POST["username"])) {
        $userErr = $ERROR_EMPTY;
        $err = true;
    }
    else $username = test_input($_POST["username"]);


    if (empty($_POST["password"])) {
        $passwordErr = $ERROR_EMPTY;
        $err = true;
    }
    else $password = test_input($_POST["password"]);

    if ($err == false) {
        $db = new DB();

        $user = $db->getUser($username);

        if ($user == null) {
            $userErr = "Error: Username does not exist!";
            $err = true;
        }

        else {

            if ($password != $user->Password) {
                $passwordErr = "Error: Incorrect password";
                $err = true;
            }

            if ($err == false) {
                session_start();

                $_SESSION["username"] = $user->Username;
                header('Location: index.php');
                die();
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
        <div class="col-sm-6 text-left forum-container">
            <!-- Actual content -->

            <h1>Log in</h1>

            <? if ($registered) echo "<p class='text-info'>Register successful, please log in.</p>"?>

            <? if (!isset($_SESSION["username"])) {
                echo '
                    <form action="login.php" method="post" class="was-validated">
                        <div class="form-group">
                            <label for="uname">Username:</label>
                            <input type="text" class="form-control" id="uname" placeholder="Enter username" name="username" required>
                            <div class="valid-feedback">Valid.</div>
                            ' . '<p class="text-danger"><b>' . $userErr . '</b></p>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="form-group">
                            <label for="pwd">Password:</label>
                            <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password" required>
                            <div class="valid-feedback">Valid.</div>
                            ' . '<p class="text-danger"><b>' . $passwordErr. '</b></p>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                    ';
            }

            else echo 'You are already logged in.'
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