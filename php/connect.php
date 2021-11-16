<?php
session_start();

include("db.php");

// import username

$user = $_SESSION["user"];
$sql = $conn->prepare("SELECT * FROM users WHERE username = ?");
$sql->bind_param('s', $user);
$sql->execute();
$sql->store_result();
$row = fetchAssocStatement($sql);
//$photolimit = $row["payment"];

// select data

//$sql = $conn->prepare("SELECT * FROM photos WHERE username = ? LIMIT ?");
//$sql->bind_param('ss', $user, $photolimit);
$sql = $conn->prepare("SELECT * FROM photos WHERE username = ?");
$sql->bind_param('s', $user);
$sql->execute();
$sql->store_result();
$out = [];
while($row = fetchAssocStatement($sql))
    {
        array_push($out,$row);
    }
echo json_encode($out);

$conn->close();
?>