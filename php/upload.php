<?php

ini_set('display_errors', 0);

if(isset($_POST["submit"])) {
    session_start();
    $user = $_SESSION["user"];

    include("db.php");
    include('update.php');
    include('fittokml.php');
    
    $total = count($_FILES['fileToUpload']['name']);
    
    for( $i=0 ; $i < $total ; $i++ ) {
        
        $uploadOk = 1;
        
        $file = basename ($_FILES["fileToUpload"]["name"][$i]);
        $filetype = strtolower(pathinfo($file,PATHINFO_EXTENSION));

            if ($filetype == 'fit' or $filetype == 'kml' or $filetype == 'gpx' ){

                $sql = $conn->prepare("SELECT * FROM track WHERE id=(SELECT max(id) FROM track);");
                $sql->execute();
                $sql->store_result();
                $row = fetchAssocStatement($sql);
                if(empty($row)){
                  $number = 1;
                } else {
                  $number = ++$row["id"];
                }
                $file = $number."-".$user.'.'.$filetype;
                $target_file = '../track/'.$file;
                $description = $_POST["text"];
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $target_file)) {
                    if ($filetype == "fit"){
						$file = fit2kml($file);
                    }

                $sql = $conn->prepare("INSERT INTO track (file, username, description) VALUES (?,?,?)");
                $sql->bind_param('sss', $file,$user,$description);
                $sql->execute();
					echo "Success";
                } else {
                    echo "GPX upload failed, please try again";
                } 

            }else if ($filetype == 'jpg' or $filetype == 'jpeg' or $filetype == 'png' ) {
                $target_file = '../photos/'.$file;
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"][$i]);

                if($check !== false) {
                    #include('update.php');
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
                    $description = $_POST["text"];
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $target_file)) {
                        $imginfo = get_image_info($target_file);
                        info_to_db($imginfo,$description,$conn);
                        copyimage($file);
                        photorotate($file);
                        shrinkimage($file);
                        removeimage($file);
						echo "Success";
                     } else {
                      echo "Photo upload failed, please try again.";
                    } 

                } else {
                    $conn->close();
                    $uploadOk = 0;
                    echo "Photo upload failed, please try again.";
                }
                } else {
					echo "Incorrect file type.";
			}
    }
    $conn->close();
    }
?>