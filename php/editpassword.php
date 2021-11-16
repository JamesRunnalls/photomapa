<?php

if(isset($_POST["submit"])) {
session_start();
    
    
$oldpassword = $_POST["oldpassword"];
$password = $_POST["password"];
$confirmpassword = $_POST["confirmpassword"];
$submit = $_POST["submit"];

include("db.php");
    
// import username

$user = $_SESSION["user"];

// perform query

if ($submit == "Update"){
    $sql = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $sql->bind_param('s', $user);
    $sql->execute();
    $sql->store_result();
    $row = fetchAssocStatement($sql);
    if (password_verify($oldpassword, $row["password"]) and $password == $confirmpassword){
        $passwordhash = password_hash($password, PASSWORD_DEFAULT);
        $sql = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $sql->bind_param('ss', $passwordhash, $user);
        $sql->execute();
        $conn->close();
		echo('<script>window.location="../gallery.php"</script>'); 
        
    } else {
        #False Info / User doesn't exist
		echo('<script>alert("Incorrect password or passwords dont match");</script>');
        echo('<script>window.location="../gallery.php"</script>');
    }	
    
} else if ($submit == "Update Guest"){
    $sql = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $sql->bind_param('s', $user);
    $sql->execute();
    $sql->store_result();
    $row = fetchAssocStatement($sql);
    
    if ($password == $confirmpassword){
        $sql = $conn->prepare("UPDATE users SET guestpassword = ? WHERE username = ?");
        if ($password == null){
            $passwordhash = "public";
            } else {
            $passwordhash = password_hash($password, PASSWORD_DEFAULT);
        }
        $sql->bind_param('ss', $passwordhash, $user);
        $sql->execute();
        $conn->close();
		echo('<script>window.location="../gallery.php"</script>'); 
        
    } else {
        #False Info / User doesn't exist
		echo('<script>alert("Passwords dont match");</script>');
        echo('<script>window.location="../gallery.php"</script>');
    }
}
    
}
?>