<?php

// Make database connection
function dbConnection() {

    try{
        $db = new PDO('mysql:host=localhost;dbname=tietoturvatoimeksianto', 'root', 'root');
        // Set error mode
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $db;

    } catch (PDOException $e) {
        echo 'Connection failed: '.$e->getMessage();
    }

}

// Check if user with given password is correct
function checkUser($db, $username, $password) {

    // Sanitize data
    $username = filter_var($_SERVER['PHP_AUTH_USER'], FILTER_SANITIZE_STRING);
    $password = filter_var($_SERVER['PHP_AUTH_PW'], FILTER_SANITIZE_STRING);

    $user = getUser($db, $username);

    if(password_verify($password, $user["password"])) {
        return true;
    }
    // If password is incorrect
    return false;
}

// Add new user to the database
function saveUser($db, $username, $password) {

    try {
        $user = getUser($db, $username);
    
        if(!isset($user)) {
            // Add new user to database with given credentials
            $sql = "INSERT INTO user (username, password) VALUES('".$username."', '".password_hash($password, PASSWORD_DEFAULT)."')";
            $prepare = $db->prepare($sql);
            $prepare->execute();
            return true;
        }

        return false;
    }
    catch(PDOException $e) {
        echo 'Connection failed: '.$e->getMessage();
    }
}

function getUser($db, $username) {
    // Add SQL Query to variable
    $sql = "SELECT * FROM user WHERE username=:username";
    $prepare = $db->prepare($sql);
    // Define named parameter and execute query
    $prepare->execute(array(':username'=>$username));
    // Fetch user from database
    $user = $prepare->fetchAll();

    return $user[0];
}