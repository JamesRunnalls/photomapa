<?php
header('HTTP/1.1 200 OK');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../index.php');
    exit();
} else {

    $photos = $_POST['option_selection1'];
    $user = $_POST['custom'];

    if ($photos == "200 Extra"){
          $extraphotos = 200;
    } else if ($photos == "500 Extra"){
          $extraphotos = 500;
    } else if ($photos == "1000 Extra"){
          $extraphotos = 1000;
    }

    include("db.php");

    // perform query
    $sql = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $sql->bind_param('s', $user);
    $sql->execute();
    $sql->store_result();
    $row = fetchAssocStatement($sql);
    $newphotos = $row["payment"] + $extraphotos;

    $sql = $conn->prepare("UPDATE users SET payment = ? WHERE username = ?");
    $sql->bind_param('ss', $newphotos, $user);
    $sql->execute();
    $conn->close();

}
?>