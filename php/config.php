<?php
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "Results";
$servername = "sql301.ezyro.com";
$username = "ezyro_35935551";
$password = "1900b6b69d";
$dbname = "ezyro_35935551_Results";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>