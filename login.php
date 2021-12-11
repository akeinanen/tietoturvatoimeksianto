<?php
session_start();
include_once('inc/functions.php');

// If user has provided username with post request (alert box)
if(isset($_SERVER['PHP_AUTH_USER'])) {
    if(saveUser(dbConnection(), $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
        echo 'New user added!';
        exit;
    }

     // If credentials are correct
    if(checkUser(dbConnection(), $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
       // Set session username to be the same that user logged in with
        $_SESSION['username'] = $_SERVER['PHP_AUTH_USER'];
        echo "Login succeeded, welcome ".$_SESSION['username'];
        exit;
    }
}
    header('WWW-Authenticate: Basic');
    header('HTTP/1.0 401 Unauthorized');
    echo "Login failed";
    exit;
?>