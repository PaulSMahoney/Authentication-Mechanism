<?php
    include 'resources/db.inc.php';
    session_start();
    require "resources/Sanatize.php";
    include "resources/salt.php";
    include "resources/generate.php";

    $_SESSION['login_time'] = time(); //Set the login time for timeout

    //Assigning values for the logs
    $date = date("Y-m-d h:i:sa");
    $_SESSION['stamp'] = $date;
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
 
    if(isset($_POST["username"]) && isset($_POST["password"])) {//Check if the user entered a username and password
        //Sanatize Username
        $username = new Sanatize($_POST["username"]);
        $username = $username->sanatize();
        $password = $_POST["password"];
        $attempts = $_SESSION['attempts'];
        $_SESSION['username'] = $username;
        
        $hashSaltPass = saltDatabase($username, $password);

        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashSaltPass'";
        // Error checking for the sql statement
        if (!mysqli_query($con, $sql)){
            echo "Error in selecting username & password";
        }
        else {
            if(mysqli_affected_rows($con) == 0){//If nothing was selected from 
                $attempts++;//Increment attemps
                if($attempts <= 5){//Attempts check like last time
                    $log = "INSERT INTO `logs` (`username`, `ip`, `attempts`, `logStats`, `time`, `userAgent`) VALUES ('$_SESSION[username]', '$_SESSION[ip]', '$attempts', 'unsuccessful ', '$_SESSION[stamp]', '$_SESSION[USER_AGENT]')";
                    if (!mysqli_query($con, $log)){
                        echo "<script>alert('help!')</script>";
                    }
                    echo "Sorry " . $username . ". You've used " . $attempts . " out of 5 attempts!";
                    $_SESSION['attempts'] = $attempts;
                    makehtml();
                }
                else{ //Max attempts
                    $log = "INSERT INTO `logs` (`username`, `ip`, `attempts`, `logStats`, `time`, `userAgent`) VALUES ('$_SESSION[username]', '$_SESSION[ip]', '$attempts', 'Lockout', '$_SESSION[stamp]', '$_SESSION[USER_AGENT]')";
                    if (!mysqli_query($con, $log)){
                        echo "<script>alert('help!')</script>";
                    }
                    echo "Max Attempts reached";
                }
            }
            else{
                /*Log successfull attempt*/
                $log = "INSERT INTO `logs` (`username`, `ip`, `attempts`, `logStats`, `time`, `userAgent`) VALUES 
                ('$_SESSION[username]', '$_SESSION[ip]', '$attempts', 'Successful', '$_SESSION[stamp]', '$_SESSION[USER_AGENT]')";
                
                if (!mysqli_query($con, $log)){
                    echo "Error with Log query";
                }
                else{
                    $_SESSION['token'] =  bin2hex(random_bytes(32));
                    $_SESSION['logged_in'] = true;
                    header("Location: page1.php", true, 301);
                    exit();
                }
            }
        }
    }
    else {//No username was detected. Build the page and set attempts to 1. 
        $attempts = 1;
        $_SESSION['attempts'] = $attempts;
        makehtml();
    };

    function makehtml(){
        echo "
            <head>
                <title>Login</title>
                <link rel='stylesheet' href='stylesheet.css'>
            </head>
            <body>
                
                <div class ='form-style-8' >
                    <h2>Login Form</h2>
                    <form action='login.html.php' method='POST'>

                    <label for='username'><b>Username</b></label>
                    <input type='text' placeholder='Enter Username' name='username' required><br>

                    <label for='password'><b>Password</b></label>
                    <input type='password' placeholder='Enter Password' name='password' pattern = '(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]){8,}' required><br>

                    <input type = 'submit' value = 'Log in'>

                    <a href='create.php'><input type='button' value='Create account'></a>
                   
                    </form>
                </div>
            </body>
        ";
    }
?>

