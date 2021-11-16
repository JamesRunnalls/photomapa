<?php

session_start();

$lat = $_POST["lat"];
$lon = $_POST["lon"];
$file = $_POST["file"];

include("db.php");

// import username

$user = $_SESSION["user"];

// perform query

$sql = $conn->prepare("UPDATE photos SET latitude = ?, longitude = ? WHERE username = ? AND file = ?");
$sql->bind_param('ssss', $lat, $lon, $user, $file);
$sql->execute();
$conn->close();


?>