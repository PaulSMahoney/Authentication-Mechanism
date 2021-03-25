<?php
    include 'resources/db.inc.php';
    session_start();
    require "resources/Sanatize.php";
    include "resources/salt.php";

    if (!$_SESSION['logged_in']){//Check if user is logged in. If not then sign them out (Destroys all assigned session variables) 
        header("Location: logout.php");
    }
    else{
        //Max session 
        $time = 3600 - (time() - $_SESSION['login_time']); 
        if(time() - $_SESSION['login_time'] >= 3600){
            session_destroy(); // destroy session.
            header("Location: login.html.php");
            die();
        }
        $username = $_SESSION['username'];
        //Admin check for access to the logs. I'll need to unhash the password too..
        if ($_SESSION['username'] == 'ADMIN' ) {
            makeAdminHTML($username, $time);
        }else{
            makehtml($username, $time);
        }
    }
    function makehtml($n, $t){
        echo "
            <head>
                <link rel='stylesheet' href='stylesheet.css'>
                <title>Page 2</title>
                <div class='topnav'>
                    <a href='page1.php'>Page 1</a>
                    <a href='page2.php'>Page 2</a>
                    <a href='change.php'>Change Password</a>
                    <a href='logout.php'>Log out</a>
                </div>
            </head>

            <body >
                <div class ='form-style-8' >
                    <h2>Greetings $n! Remaining time : $t </h2>
                    <h3>Why do we use it?</h3>
                    <p>
                        It is a long established fact that a reader will be distracted by the readable content 
                        of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-
                        or-less normal distribution of letters, as opposed to using 'Content here, content here', making 
                        it look like readable English. Many desktop publishing packages and web page editors now use Lorem 
                        Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still 
                        in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on 
                        purpose (injected humour and the like).
                    </p>
                </div>
            </body>
        ";
    }
    function makeAdminHTML($n, $t){
        echo "
            <head>
                <link rel='stylesheet' href='stylesheet.css'>
                <title>Page 2</title>
                <div class='topnav'>
                    <a href='page1.php'>Page 1</a>
                    <a href='page2.php'>Page 2</a>
                    <a href='logs.php'>Logs</a>
                    <a href='change.php'>Change Password</a>
                    <a href='logout.php'>Log out</a>
                </div>
            </head>

            <body >
                <div class ='form-style-8' >
                    <h2>Greetings $n! Remaining time : $t </h2>
                    <h3>Why do we use it?</h3>
                    <p>
                        It is a long established fact that a reader will be distracted by the readable content 
                        of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-
                        or-less normal distribution of letters, as opposed to using 'Content here, content here', making 
                        it look like readable English. Many desktop publishing packages and web page editors now use Lorem 
                        Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still 
                        in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on 
                        purpose (injected humour and the like).
                    </p>
                </div>
            </body>
        ";
    }
?>