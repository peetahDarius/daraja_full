<?php

if (isset($_POST['submit'])) {
    #variables
$username = $_POST['name'];    
$pwd = $_POST['pwd'];    

require_once'conn.inc.php';
require_once'functions.inc.php';

if (emptyInputLogin( $username ,$pwd)!== false) {
    header("Location: ../login.php?error=emptyinput");
    exit();
}
loginUser($conn , $username, $pwd);

}
else{
    header("location:../login.php");
    exit();
}