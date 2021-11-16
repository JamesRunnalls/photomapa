<?php

if(isset($_POST["submit"])) {
session_start();
$file = $_POST["file"];
$description = $_POST["description"];
$date = $_POST["date"];
$altitude = $_POST["altitude"];
$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];
$deg = $_POST["rotate"];
$submit = $_POST["submit"];

include("db.php");

// import username

$user = $_SESSION["user"];

// perform query

if ($submit == "Update"){
    if ($deg != 0){
        $filen = substr($file, 0, -4)."r".substr($file, -4);
    } else {
        $filen = $file;
    }
    $sql = $conn->prepare("UPDATE photos SET file = ?, datetime = ?, description =  ?, altitude = ?, latitude = ?, longitude = ? WHERE username = ? AND file = ?");
    $sql->bind_param('ssssssss', $filen, $date, $description, $altitude, $latitude, $longitude, $user, $file);
    $sql->execute();
    $conn->close();
    if ($deg != 0) {
        rename('../photos_c/'.$file,'../photos_c/'.$filen);
        ini_set('memory_limit', '512M');
        $img = imagecreatefromjpeg('../photos_c/'.$filen);
        if (!$img){$img = imagecreatefromstring(file_get_contents('../photos_c/'.$filen));}
        $rotate = imagerotate($img, $deg, 0);
        imagejpeg($rotate, '../photos_c/'.$filen);
        imagedestroy($img);
    }
    header('Cache-Control: no-cache');
    header('Pragma: no-cache');
    header( "Location: ../gallery.php#".$filen );
    
    
} else if ($submit == "Delete"){
    $sql = $conn->prepare("DELETE FROM photos WHERE username = ? AND file = ?");
    $sql->bind_param('ss', $user, $file);
    $sql->execute();
    unlink('../photos_c/'.$file);
    $conn->close();
    header('Cache-Control: no-cache');
    header('Pragma: no-cache');
    header( "Location: ../gallery.php" );
}

}

?>