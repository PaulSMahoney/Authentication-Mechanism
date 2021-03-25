<meta http-equiv="refresh" content= "5;logout.php"/>
<?php
    include 'resources/db.inc.php';
    session_start();
    require "resources/Sanatize.php";
    include "resources/salt.php";

    if (!$_SESSION['logged_in']){//Check if user is logged in. If not then sign them out (Destroys all assigned session variables) 
        header("Location: logout.php");
    }
    else{
        if ($_SESSION['username'] == 'ADMIN' ) {
            //Max session 
            $time = 3600 - (time() - $_SESSION['login_time']); 
            if(time() - $_SESSION['login_time'] >= 3600){
                session_destroy(); // destroy session.
                header("Location: login.html.php");
                die();
            }
            $username = $_SESSION['username'];
            makehtml($username, $time);
            //Build Table
            
            $sql = "SELECT * FROM logs";
                $result = $con->query($sql);
                echo "<table border='1'>
                    <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>IP</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>User Agent</th>
                    </tr>";
                while($row = mysqli_fetch_array($result))
                {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['ip'] . "</td>";
                    echo "<td>" . $row['logStats'] . "</td>";
                    echo "<td>" . $row['time'] . "</td>";
                    echo "<td>" . $row['userAgent'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";   
            }else{
                header("Location: logout.php");
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
                    <a href='logs.php'>Logs</a>
                    <a href='change.php'>Change Password</a>
                    <a href='logout.php'>Log out</a>
                </div>
            </head>

            <body >
                <div class ='form-style-8' >
                    <h2>Greetings $n! Remaining time : $t </h2>
                    
                </div>
            </body>
        ";
    }

?>