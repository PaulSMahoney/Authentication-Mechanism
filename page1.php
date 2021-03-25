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
                <title>Page 1</title>
                <div class='topnav'>
                    <a href='page1.php'>Page 1</a>
                    <a href='page2.php'>Page 2</a>
                    <a href='change.php'>Change Password</a>
                    <a href='logout.php'>Log out</a>
                </div>
            </head>

            <body>
                <div class ='form-style-8' >
                    <h2>Greetings $n! Remaining time : $t </h2>
                    <h3>What is Lorem Ipsum?</h3>
                    <p>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                        when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
                        It has survived not only five centuries, but also the leap into electronic typesetting, remaining 
                        essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets 
                        containing Lorem Ipsum passages, and more recently with desktop publishing software like 
                        Aldus PageMaker including versions of Lorem Ipsum.
                    </p>
                </div>
            </body>
        ";
    }
    function makeAdminHTML($n, $t){
        echo "
            <head>
                <link rel='stylesheet' href='stylesheet.css'>
                <title>Page 1</title>
                <div class='topnav'>
                    <a href='page1.php'>Page 1</a>
                    <a href='page2.php'>Page 2</a>
                    <a href='logs.php'>Logs</a>
                    <a href='change.php'>Change Password</a>
                    <a href='logout.php'>Log out</a>
                </div>
            </head>

            <body>
            <div class ='form-style-8' >
                <h2>Greetings $n! Remaining time : $t </h2>
                <h3>What is Lorem Ipsum?</h3>
                <p>
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                    when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
                    It has survived not only five centuries, but also the leap into electronic typesetting, remaining 
                    essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets 
                    containing Lorem Ipsum passages, and more recently with desktop publishing software like 
                    Aldus PageMaker including versions of Lorem Ipsum.
                </p>
            </div>
        </body>
        ";
    }
?>