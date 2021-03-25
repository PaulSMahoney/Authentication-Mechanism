<?php
    include 'db.inc.php';
    //error_reporting(0);
    function Salt($passw, $salt) {
        $hashSaltPass = md5($passw .  $salt);//MD5 calculates the hash of the string
        return $hashSaltPass;
    }
    
    function saltDatabase($user, $pass){
        include 'db.inc.php';
 
        $salt = "SELECT salt FROM `users` where username = '$user' ";
        $querySalt = mysqli_query($con, $salt);
        $x =  mysqli_fetch_array($querySalt);
        $hashSaltPass = md5($pass . $x[0]);

        return $hashSaltPass;
    }
?>