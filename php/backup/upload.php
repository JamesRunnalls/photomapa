<?php

// Check password is correct

if($_POST["psw"] == "Photos"){
    
$uploadOk = 1;   
$file = basename ($_FILES["fileToUpload"]["name"]);
$filetype = strtolower(pathinfo($file,PATHINFO_EXTENSION));
if ($filetype == 'fit'){
    $target_file = '../fit/'.$file;
}else if ($filetype == 'gpx'){
     $target_file = '../gpx/'.$file;
}else if ($filetype == 'jpg' or $filetype == 'jpeg') {
    $target_file = '../photos/'.$file;
    if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
        echo "Sorry, that was a fake image ";
    }
    }
    }
if (file_exists($target_file)) {
    echo "Sorry, file already exists ";
    $uploadOk = 0;
}   

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "hence your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    // collect description and save as text file
    $content = $_POST["text"];
    $text = "../txt/" . substr(basename($_FILES["fileToUpload"]["name"]), 0, -4) . ".txt";
    $fp = fopen($text,"wb");
    fwrite($fp,$content);
    fclose($fp);
    include('fittokml.php');
    include('gpxtokml.php');
    include('update.php');
    header('Cache-Control: no-cache');
    header('Pragma: no-cache');
    header( "Location: http://jamesrunnalls.com/photogallery.html" );

    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

}


?>