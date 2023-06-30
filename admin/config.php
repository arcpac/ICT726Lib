<?php 
// // DB credentials.
// define('DB_HOST','localhost');
// define('DB_USER','root');
// define('DB_PASS','');
// define('DB_NAME','library');
// // Establish database connection.
// try
// {
// $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
// }
// catch (PDOException $e)
// {
// exit("Error: " . $e->getMessage());
// }

$server_name = 'localhost';
$s_username = 'root';
$s_password = '';
$database_name = 'library';

$connection = mysqli_connect($server_name, $s_username, $s_password, $database_name);
return $connection;