<?php

include("db.php");
include('update.php');

$files = scandir('../photos/');

$user = "jamesrunnalls";

foreach ($files as $val => $fileo) {
    
    $filetype = strtolower(pathinfo($fileo,PATHINFO_EXTENSION));
        
    if ($filetype == 'jpg' or $filetype == 'jpeg' or $filetype == 'png' ) {

    $target_fileo = '../photos/'.$fileo;
    $sql = $conn->prepare("SELECT * FROM photos WHERE id=(SELECT max(id) FROM photos);");
    $sql->execute();
    $sql->store_result();
    $row = fetchAssocStatement($sql);
    if(empty($row)){
      $number = 1;
    } else {
      $number = ++$row["id"];
    }
    $file = $number."-".$user.'.'.$filetype;
    $target_file = '../photos/'.$file;
    rename($target_fileo,$target_file);
    $description = " ";
    $imginfo = get_image_info($target_file);
    info_to_db($imginfo,$description,$conn);
    copyimage($file);
    photorotate($file);
    shrinkimage($file);
    removeimage($file);
    }
}


?>