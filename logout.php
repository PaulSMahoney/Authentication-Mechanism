<?php
    session_start();
    include 'resources/db.inc.php';

    //Logs


    //Unset session variables
    session_unset();
    //Destroy session   
    session_destroy();
    //Return to login
    header("Location: login.html.php");
?>