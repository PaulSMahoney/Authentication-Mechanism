<?php
    include 'resources/db.inc.php';
    session_start();
    require "resources/Sanatize.php";
    include "resources/salt.php";

    if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["cpassword"])) {//Check if the user has submitted anything
        //Sanatize Username
        $username = new Sanatize($_POST["username"]);
        $username = $username->sanatize();
        $password = $_POST["password"];
        $cpassword = $_POST["cpassword"];
        
        //SQL to check if user exists
        $sqlQ = "SELECT * FROM users WHERE username='$username'";
        $sqlQ2 = mysqli_query($con, $sqlQ);
        
        if (mysqli_num_rows($sqlQ2) > 0) {//
            echo "<script>alert('Username taken! Try again.')</script>";
            makehtml();	
        }
        else {//User doesn't exist!
            if($password == $cpassword){//Check if the passwords match
                $salt = uniqid (mt_rand());//Generate a random unique number using php functions
                $hashSaltPass = Salt($password, $salt);
                $sql = "INSERT INTO `users` ( `username`, `password`, `salt`) VALUES ('$username', '$hashSaltPass', '$salt')";
                if (!mysqli_query($con, $sql)) {
                    echo "Error in inserting username & password" . mysqli_error($con);
                }
                echo "<script>alert('Success!'); window.location.href='login.html.php';</script>";      
            }
            else{//Passwords do not match
                echo "<script>alert('Passwords do not match! Try again.'); </script>";
                makehtml();
            }
        }   
    }
    else{ //If user is just directed from Login then create the page
        makehtml();
    };
    
    //This function creates the HTML from the below template
    function makehtml(){
        echo "
            <head>
                <title>Create an Account!</title>
                <link rel='stylesheet' href='stylesheet.css'>
            </head>

            <body>
                <div class ='form-style-8' >
                    <h2>Create Account</h2>
                    <p>Password Requirements, Must contain at least 1: </p
                    <ul>
                        <li>Uppercase</li>
                        <li>Lowercase</li>
                        <li>Number</li>
                        <li>Special Character</li>
                        <li>Min length 8</li><br>
                    </ul>
                    <form action='create.php' method='POST'>
                        <label for='username'><b>Username</b></label>
                        <input type='text' placeholder='Enter Username' name='username' required>

                        <label for='password'><b>Password</b></label>
                        <input type='password' placeholder='Enter Password'  name='password' pattern = '(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]){8,}' required>
                        
                        <label for='cpassword'><b>Confirm</b></label>
                        <input type='password' placeholder='Enter Password'  name='cpassword' pattern = '(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]){8,}' required>

                        <input type = 'submit' value ='Create account' ><br><br>
                        <a href='login.html.php'><input type='button' value='Cancel!'></a>
                    </form>
                </div>
            </body>
        ";
    }
?>