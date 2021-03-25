<meta http-equiv="refresh" content= "5;logout.php"/>
<?php
    include 'resources/db.inc.php';
    require "resources/Sanatize.php";
    include "resources/salt.php";
    session_start();
   

    if (!$_SESSION['logged_in']){
        header("Location: logout.php");//Call the logout to destroy session and all variables created
        //header("Location: login.html.php");
    }
    else{
        //Session Timer
        $time = 3600 - (time() - $_SESSION['login_time']);
        if(time() - $_SESSION['login_time'] >= 3600){
            session_destroy(); // destroy session.
            header("Location: login.html.php");
            die();
        }
        
        //if logged in
        if (isset($_SESSION["username"])) {
            //get form infromation

            $token = $_SESSION['token'];
            $token2 = $_GET['token'];
            
            if($token == $token2){
                if (isset($_GET['opassword']) && isset($_GET['password']) && isset($_GET['cpassword'])) {
                    $oldPass = $_GET['opassword'];
                    $newPass = $_GET['password'];
                    $conPass = $_GET['cpassword'];  
                    //Retrieve current data
                    $userName = $_SESSION['username'];
                    $oldHash = saltDatabase($userName, $oldPass);
                    $sql=  "SELECT * FROM users WHERE username = '$userName' AND password = '$oldHash'";//Select statement that gathers the previous used password with that account name
                    if(!mysqli_query($con, $sql)){ // Check for error in SQL statement
                        echo "Change.php error in select query! " . mysqli_error($con);
                    }else {//No error we can move on! Now for some password checking
                        if(mysqli_affected_rows($con) == 0){ //First to check if the old password is correct
                            echo "<script>alert('Password Incorrect! Try again.')</script>";
                        } 
                        else if($oldPass == $newPass){ //check if newPassword is the same as the old one
                            echo "<script>alert('Cant change to the same password! Try again.')</script>";
                        }
                        else if ($newPass != $conPass){ //Check if the new password and confirm password match
                            echo "<script>alert('New password doesnt match! Try again.')</script>";
                        }
                        else{//Finally everything is okay so we can change password
                            $salt = uniqid (mt_rand());
                            $hashSaltPass = Salt($newPass, $salt);
                            $sql2 = "UPDATE users SET password =  '$hashSaltPass', salt = '$salt' WHERE username = '$userName'";
                            if(!mysqli_query($con, $sql2)){ // Check for error in SQL2 statement
                                echo "Error with Update query SQL2! " . mysqli_error($con);
                            }else{//No errors, time to inform the user
                                if(mysqli_affected_rows($con) == 0){ //Check if the SQL updated anything
                                    echo "<script>alert('Error updating! Try again.')</script>";
                                } 
                                else{
                                    //Success, call the logout function to destory the session 
                                    echo "YAY";
                                    //header("Location: logout.php");
                                }
                            }
                        }
                    }  
                }
            }
        }
    }
    
    makehtml($time, $token);

    function makehtml($t, $token){
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
                
                <h2>Change Password! Remaining time : $t</h2>
                <p>Password Requirements, Must contain at least 1: </p
                <ul>
                    <li>Uppercase</li>
                    <li>Lowercase</li>
                    <li>Number</li>
                    <li>No Special Character</li>
                    <li>Min length 8</li><br>
                </ul>

                <form action='change.php' method='GET'>

                <label for='token'></label>
                <input type='hidden' name='token' value='$token' /> 

                <label for='opassword'><b>Enter old password</b></label>
                <input type='password' placeholder='Enter old password'  name='opassword' pattern = '(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]){8,}'   required><br>
        
                <label for='password'><b>Enter new password</b></label>
                <input type='password' placeholder='Enter new password'  name='password' pattern = '(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]){8,}' required><br>
                
                <label for='cpassword'><b>Confirm the password</b></label>
                <input type='password' placeholder='Confrim new password'  name='cpassword' pattern = '(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]){8,}' required><br>

                <input type = 'submit' value ='Change password!' >
                <a href='page1.php'><input type='button' value='Cancel!'></a>
                </form>
            </div>
            </body>
        ";
    }
?>