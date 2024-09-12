<?php
// connection.php
require_once __DIR__ . '/config.php';

function Connect() {
    $dbhost = DB_HOST;
    $dbuser = DB_USER;
    $dbpass = DB_PASS;
    $dbname = DB_NAME;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());

    }
    return $conn;
}
