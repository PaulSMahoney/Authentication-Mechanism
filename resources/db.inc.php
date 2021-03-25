<!--Name of work unit: db.inc.php
   Purpose: Universal connection to be called within every php file 
   Author: Paul Mahoney C00227807
   -->
<?php 
    $hostname = "localhost";            //Name of Host or IP address
    $username = "root";			        //MySql username
    $password = "";			            //MySql password
    $dbname = "mydbtest";				//Database Name
  
    // Connect to MySQL
    $con = new mysqli($hostname, $username, $password);
    if ($con->connect_error) {
	    die("Connection failed: " . $con->connect_error);
    }
    //Create the database if it doesnt exist
    if (!mysqli_select_db($con,$dbname)){
        $sql = "CREATE DATABASE IF NOT EXISTS mydbtest";

        if ($con->query($sql) === TRUE) {
            echo "Database created successfully";
        }else {
            echo "Error creating database: " . mysqli_connect_error();
        }
    } 
?>

