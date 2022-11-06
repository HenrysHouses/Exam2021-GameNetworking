<?php

//start session - again..
session_start();

//unset the session variables
$_SESSION = array();

//destroy the session
session_destroy();

//redirect the user to the login page
header("location: ./login.php");
exit;
?>