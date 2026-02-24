<?php
$host = "127.0.0.1";
$user = "root";
$password = "";
$dbname = "mythic_craft";
$port = 3307;

$conn = new mysqli($host, $user, $password, $dbname, $port);

if ($conn->connect_error) {
    die(json_encode([
        "status" => "error",
        "message" => "Database connection failed"
    ]));
}

$conn->set_charset("utf8");

?>