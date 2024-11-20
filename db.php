<?php
$host = "database-1.cyvzwzewn9v9.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "password123";
$database = "CraftyCreationsDatabase";
$mysql = new PDO("mysql:host=".$host.";dbname=".$database,
$username, $password);
echo "Database Linked <br>";
?>