<?php
session_start();
session_destroy();
header('location: ../index.php');
die('Connection unsuccessful' . mysqli_connect_error());
