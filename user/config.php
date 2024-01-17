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
// return $dbh;
// }
// catch (PDOException $e)
// {
// exit("Error: " . $e->getMessage());
// }
$server_name = 'libruadmin01!';
$username_server = 'Test123!.';
$password_server = 'Test123!.';
$database_name = 'library';

$connection = mysqli_connect($server_name, $username_server, $password_server, $database_name);
return $connection;