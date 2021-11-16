<?php
session_start();
$configs = include('config.php');

# Check is user is alreadt logged in
if(isset($_SESSION['sig']))
{
	#User is already logged in
	echo("<script>window.location='gallery.php'</script>");	
}

#Check if the login form was submitted
if(isset($_REQUEST['submit']))
{
	#Perform login action
	$username=$_REQUEST['username'];
	$password=$_REQUEST['password'];
	
    // Create connection

    include("php/db.php");

    // select data
    
    
    $sql = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $sql->bind_param('s', $username);
    $sql->execute();
    $sql->store_result();
    $row = fetchAssocStatement($sql);
    
	if(empty($row))
	{
		echo('<script>sessionStorage.setItem("message","Username not recognised please try again.");</script>');		
		
	}
	else if (password_verify($password, $row["password"])){
        $_SESSION['sig']="main";
        $_SESSION['user']=$username;
		echo('<script>window.location="gallery.php"</script>'); 
    } else if (password_verify($password, $row["guestpassword"])){
        $_SESSION['sig']="guest";
        $_SESSION['user']=$username;
		echo('<script>window.location="guestgallery.php"</script>');
        
    } else if ($row["guestpassword"] == "public" and $password == null){
        $_SESSION['sig']="guest";
        $_SESSION['user']=$username;
		echo('<script>window.location="guestgallery.php"</script>');
        
    } else {
        #False Info / User doesn't exist
		echo('<script>sessionStorage.setItem("message","Incorrect password please try again or reset your password below.");</script>');   
    }	
} else if(isset($_REQUEST['resetpassword'])){
	$username = $_POST["username"];
    $email = $_POST["email"];

    include("php/db.php"); #
    
    $sql = $conn->prepare("SELECT * FROM users WHERE username = ? and email = ?");
    $sql->bind_param('ss', $username, $email);
    $sql->execute();
    $sql->store_result();
    $row = fetchAssocStatement($sql);
    if (empty($row)){
        echo('<script>sessionStorage.setItem("message","Unable to reset password; username and email address do not match our records. Try again or contact us at contact@webhydraulics.com.");</script>');
    } else {
        $password = rand(1000000,9999999);
        $passwordhash = password_hash($password, PASSWORD_DEFAULT);
        $sql = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $sql->bind_param('ss', $passwordhash, $username);
        $sql->execute();
        $conn->close();
        
        require 'php/phpmailer/PHPMailerAutoload.php';

        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP(); 
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );// Set mailer to use SMTP
        $mail->Host = $configs['mail_host'];  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;      
        $mail->SMTPDebug = 3; // Enable SMTP authentication
        $mail->Username = 'donotreply@photomapa.com';                 // SMTP username
        $mail->Password = $configs['mail_password'];                         
        $mail->SMTPSecure = 'ssl';                 
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->setFrom('donotreply@photomapa.com', 'Photomapa');    // Add a recipient
        $mail->addAddress($email);               // Name is optiona
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Photomapa Password Reset';
        $mail->Body    = "Dear ".$username.", <br><br> Your password has been reset. <br><br> Your temporary password is: ".$password."<br><br> Please login and change this password. <br><br> Thanks <br><br> Photomapa";
        $mail->AltBody = "Dear ".$username.", \n \n Your password has been reset. \n \n Your temporary password is: ".$password."\n \n Please login and change this password. \n \n Thanks \n \n Photomapa";

        if(!$mail->send()) {
         	echo('<script>sessionStorage.setItem("message","Unable to reset password, please try again or contact us at contact@photomapa.com.");</script>');
        } else {
            echo('<script>sessionStorage.setItem("message","Temporary password has been sent to your email.");</script>');
            echo('<script>window.location="login.php"</script>');
        }
        
    }
} else {
	echo('<script>window.location="index.php"</script>');
}


?>

<!doctype html>
<html>
<head>
<meta name="description" content="Photomapa is a dynamic photo gallery and map that lets you store your global memories.">
<meta name="author" content="James Runnalls">
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" href="css/style.css" type="text/css">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<title>Photomapa - Login</title>
</head>

<body>
		
<cr>&copy; 2018 photomapa.com &nbsp</cr>
	    
<div class="outside">
<img src="img/logo.png" class="logo2">
	<div id="signupform" class="signupform">
		<div class="form-outer">
		<div class="form-top">
			<div style="font-size:25px">Login. </div><br> <div id="errors" style="font-size:16px;color:red;text-align:left;"></div>
		</div>
		<div class="form-bottom">
			<form method="post" action="login.php" autocomplete="on" class="login">
				Username: <br>
				<input type="text" name="username" placeholder="Username" autocomplete="username" style="width:100%;height:25px;color:black;border:1px solid #c7c7c7;"><br>
				Password: <br>
				<input type="password" name="password" placeholder="Password" autocomplete="current-password" style="width:100%;height:25px;color:black;border:1px solid #c7c7c7;"><br><br>
				<input type="submit" name="submit" value="Sign In" class="bluebutton" style="width:100%;height:40px;"><br>
			</form><br>
			<div style="text-align:left;font-size:12px">
			Don't have an account? Sign up <a href="index.php"><b>here</b>.</a>	<br><br>
		
			<a href="javascript:unhide()" style="text-decoration:none;color:black;">Forgotten password?</a></div><br><br>
			<div id="forgottenpassword" style="display:none">
			<form method="post" action="login.php" autocomplete="on" class="login">
				Username: <br>
				<input id="username" type="text" name="username" placeholder="Username" style="width:100%;height:25px;color:black;border:1px solid #c7c7c7;" autocomplete="username"><br>
				Email: <br>
				<input id="email" type="email" name="email" placeholder="Email" style="width:100%;height:25px;border:1px solid #c7c7c7;" autocomplete="email"><br><br>
				<input type="submit" name="resetpassword" value="Reset Password" class="bluebutton" style="width:100%;height:40px;">
			</form>
			</div>
		</div>
		</div>
	</div>	
	</div>
	<br><br>
 <div id="background"></div>
 
</body>
<script src="js/function.js"></script>
<script>
	function unhide(){
		var dis = document.getElementById("forgottenpassword");
		if (dis.style.display == "block"){
			dis.style.display = "none";
		} else {
			dis.style.display = "block";
		}
	}
	
	var message = sessionStorage.getItem("message");
	document.getElementById("errors").innerHTML = message;
	sessionStorage.setItem("message","");
	sessionStorage.removeItem("message");
</script>
</html>