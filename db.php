<?php
$host = "localhost";
$port = "5432";
$dbname = "quiz_app";
$user = "postgres";
$password = "#Nishant17#";  // 👈 the same password you set in step 2

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Error: Could not connect to database. " . pg_last_error());
}
?>
