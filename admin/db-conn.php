<?php

// Database credentials

$servername = "localhost";
$username = "root";
$dbpassword = "your-admin-db-password";
$dbname = "your-admin-db-name" ;

/* Attempt to connect to MySQL database */
$mysqli = new mysqli($servername, $username, $dbpassword, $dbname);
 
// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
?>
