<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prisma";
$mysql = "mysql:host=$servername;dbname=$dbname";

$db_filename = __DIR__ . "/database.db";
$sqlite = "sqlite:$db_filename";

try {
  $db = new PDO($sqlite, $username, $password);
  // set the PDO error mode to exception
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->query("CREATE TABLE IF NOT EXISTS `accounts` (`id` INTEGER PRIMARY KEY, `name` varchar(50) NOT NULL, `age` int NOT NULL)");
  // echo "Connected successfully";
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}