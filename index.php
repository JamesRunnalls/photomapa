<?php
session_start();

# Check is user is alreadt logged in
if(isset($_SESSION['sig']))
{
	#User is already logged in
	echo("<script>window.location='gallery.php'</script>");	
}

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
        echo('<script>sessionStorage.setItem("message","Please complete all the fields before submitting the form.");</script>');
    } else if ($password != $confirmpassword) {
        echo('<script>sessionStorage.setItem("message","Passwords do not match please try again.");</script>');
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
		echo('<script>sessionStorage.setItem("message","Sorry user name already exists. Please try another name.");</script>');
    }

    }
    
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
	
<!-- Sign In Footer -->   
    
<footer>
    <form method="post" action="login.php" autocomplete="on">
        <div class="footerbox"><input id="username" type="text" name="username" placeholder="Username" class="footer-input" autocomplete="username">
        <input id="password" type="password" name="password" placeholder="Password" class="footer-input" autocomplete="current-password">
        <input type="submit" name="submit" value="Sign In" class="bluebutton" style="border:none;"></div>
    </form> 
</footer>
		
<cr>&copy; 2018 photomapa.com &nbsp</cr>
	    
<div class="outside">
<img src="img/logo.png" class="toplogo">
	<div id="signupform" class="signupform">
		<div class="imgblock">
		<img src="img/logo.png" alt="photomapa" class="logo">
		<div id="shown" class="textbox">Map your adventures, record your travels.<br> The perfect home for your georeferenced photos.<br><a href="javascript:seefeatures()" style="color:black;text-decoration:none;font-size:16px;"><b>See features.</b></a></div>
		<div id="hidden" class="textbox" style="display:none;">
			<a href="javascript:seefeatures()" style="color:black;text-decoration:none;font-size:16px;"><b>Hide features.</b></a>
			<ul style="text-align:left;font-size:16px;margin-top:0;">
				<li>Beautiful galley.</li>
				<li>Overview map with the location of all your photos.</li>
				<li>Easily georeference images without location information.</li>
				<li>Add your gps tracks to the map.</li>
				<li>Open or password protected guest access to share your photos.</li>
			</ul>
		</div>
		</div>
		<div class="form-outer">
		<div class="form-top">
			<div style="font-size:25px">Join <img src="img/logo.png" style="width:150px;margin-bottom:-7px;">. </div><br> <div style="font-size:18px;">Get started - it's free.</div>
		</div>
		<div class="form-bottom">
			<form method="post" action="index.php" autocomplete="off" class="login">
				Email: <br>
				<input class="index" type="email" autocomplete="email" name="inemail" style="width:100%;height:20px;border:1px solid #c7c7c7;"><br>
				Username: <br>
				<input class="index" type="text" autocomplete="username" name="username" style="width:100%;height:20px;border:1px solid #c7c7c7;"><br>
				Password: <br>
				<input class="index" type="password" autocomplete="new-password" name="password" style="width:100%;height:20px;border:1px solid #c7c7c7;"><br>
				Confirm Password: <br>
				<input class="index" type="password" autocomplete="new-password" name="confirmpassword" style="width:100%;height:20px;border:1px solid #c7c7c7;"><br>
				<h8 style="padding-top:5px;">By signing up, you agree to our <a href="termsandconditions.html">Terms</a> & <a href="privacypolicy.html">Privacy Policy.</a></h8><br><br>
				<input type="submit" name="register" value="Join Now" style="width:100%;height:35px;">
				<div id="message" style="color:red;font-size:16px;line-height:1.2;padding-top:10px;"></div>
			</form>
		</div>
		</div>
	</div>	
	</div>
	<br><br>
 <div id="background"></div>
</body>
<script src="js/function.js"></script>
<script>
	function seefeatures(){
		if (document.getElementById("hidden").style.display == "none") {
			document.getElementById("hidden").style.display = "block";
			document.getElementById("shown").style.display = "none";
			
		} else {
			document.getElementById("hidden").style.display = "none";
			document.getElementById("shown").style.display = "block";
		}
		
	}
	var message = sessionStorage.getItem("message");
	document.getElementById("message").innerHTML = message;
	sessionStorage.setItem("message","");
	sessionStorage.removeItem("message");
</script>
</html>