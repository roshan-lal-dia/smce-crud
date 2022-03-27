<?php

// Database credentials

$servername = "localhost";
$username = "root";
$dbpassword = "369;'0/";
$dbname = "smce-crud" ;

/* Attempt to connect to MySQL database */
$mysqli = new mysqli($servername, $username, $dbpassword, $dbname);
 
// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
?>