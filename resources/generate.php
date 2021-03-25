<?php
    error_reporting(0);
    include 'db.inc.php';
    //include "salt.php";
    
    //Create the Users table
    $create_users = 
    "CREATE TABLE mydbtest.users(
        id INT(11) NOT NULL AUTO_INCREMENT, 
        username varchar(255), 
        password varchar(255), 
        salt varchar(255), 
        PRIMARY KEY (id,username)
    )";

    //Create the logs table
    $create_logs = 
    "CREATE TABLE mydbtest.logs(
        id INT(11) NOT NULL AUTO_INCREMENT, 
        username varchar(255), 
        ip varchar(255),
        attempts varchar(255),
        logStats varchar(255),
        time varchar(255),
        userAgent varchar(255),
        PRIMARY KEY (id,username)
    )";

    //Inject the admin into the users table
    $adminPass = 'SAD_2021!'; 
    $salt = uniqid(mt_rand());
    $hashSaltPass = Salt($adminPass ,  $salt);
    $sql = "INSERT INTO users ( `id`, `username`, `password`, `salt`) VALUES ('1','ADMIN', '$hashSaltPass', '$salt')";
    
    //Legacy code. I initially had a button to generate the database instead of having it run every time with the log in
        //echo "<script>alert('Complete! You will be redirected back promptly!')</script>;";
        //header( "refresh:20;url=http://localhost/project/login.html.php" );
        //header('Location:login.html.php');
    
    if (!mysqli_query($con, $create_users)) {
        //echo "Error with creating the users table" . mysqli_error($con). "<br>";
    }
    if (!mysqli_query($con, $create_logs)) {
        //echo "Error with creating the logs table " . mysqli_error($con). "<br>";
    }
    if (!mysqli_query($con, $sql)) {
        //echo "Error with creating the admin " . mysqli_error($con). "<br>";
    }
?>