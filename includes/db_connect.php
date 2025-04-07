<?php

$host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";     
$username = "2chHuJJjXN4T7Z9.root";       
$password = "Mcg8yMpNokw8nhve";           
$database = "test";       
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>
