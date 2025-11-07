<?php

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PORT', '3306');
define('DB_PASS', '');
define('DB_NAME', 'bookshop-website');

// Create mysqli connection and expose as $conn for other scripts
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, (int)DB_PORT);
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

?>