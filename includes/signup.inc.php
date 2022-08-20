<?php

if(isset($_POST['submit'])){
//setting all data from the form into variables

$name = $_POST['name'];
$email = $_POST['email'];
$username = $_POST['uid'];
$pwd = $_POST['pwd'];
$pwdRepeat = $_POST['pwd_repeat'];

require_once('conn.inc.php');
require_once('functions.inc.php');

if (emptyInputSignup($name,$email, $username ,$pwd , $pwdRepeat)!== false) {
    header("Location: ../sign-up.php?error=emptyinput");
    exit();
}


if (invalidEmail( $email)!== false) {
    header("Location: ../sign-up.php?error=invalidemail");
    exit();
}
if (pwdMatch( $pwd , $pwdRepeat)!== false) {
    header("Location: ../sign-up.php?error=passnotsame");
    exit();
}
if (uidExists( $conn , $username, $email)!== false) {
    header("Location: ../sign-up.php?error=usernametaken");
    exit();
}

createUser($conn,$name,$email, $username ,$pwd );


}else{
//redirect to form
    header("Location: ../sign-up.php");    
    exit();
}