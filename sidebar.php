<?php

if(!isset($_SESSION))
{
    session_start();
}

if (!isset($_SESSION["username"])) {
    echo "Not logged in";
}

else {
    echo "Hello " . $_SESSION["username"] . "!";
}

?>