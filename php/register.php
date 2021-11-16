<?php
session_start();

if(isset($_REQUEST['register']))
{

    #Perform Registration Action	
    $username=$_REQUEST['username'];
    $password=$_REQUEST['password'];
    $confirmpassword=$_REQUEST['confirmpassword'];
    $passwordhash = password_hash($password, PASSWORD_DEFAULT);
    $guestpasswordhash = "public";
    $email = $_REQUEST['inemail'];
    
    if ($username==null or $password==null or $email==null ){
        echo('<script>alert("Please complete all the fields");</script>');
    } else if ($password != $confirmpassword) {
        echo('<script>alert("Passwords do not match");</script>');
    } else {

    // Create connection

    include("php/db.php");
        
    $sql = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $sql->bind_param('s', $username);
    $sql->execute();
    $sql->store_result();
    $row = fetchAssocStatement($sql);
    $freephotos = "20";
    
    if(empty($row)){
        $sql = $conn->prepare("INSERT INTO users (email, password, guestpassword, username,payment) VALUES (?,?,?,?,?)");
        $sql->bind_param('sssss',$email,$passwordhash,$guestpasswordhash,$username,$freephotos);
        $sql->execute();
        $_SESSION['sig']="main";
        $_SESSION['user']=$username;
        echo('<script>window.location="../gallery.php"</script>'); 
	} else {
        #False Info / User doesn't exist
		echo('<script>alert("Sorry user name already exists.");</script>');
    }

    }
    
}

?>