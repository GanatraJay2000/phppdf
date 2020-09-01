<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "phppdf";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$create_files_table = "CREATE TABLE IF NOT EXISTS files (
id INT(6) PRIMARY KEY AUTO_INCREMENT,
filename TEXT NOT NULL,
text json NOT NULL,
subjects json NOT NULL)";
$conn->query($create_files_table);




// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
// $create_files_table = "CREATE TABLE IF NOT EXISTS files (
// id INT(6) PRIMARY KEY AUTO_INCREMENT,
// filename TEXT NOT NULL,
// students_table_number NUMERIC NOT NULL,
// subjects json NOT NULL);";
// $conn->query($create_files_table);
