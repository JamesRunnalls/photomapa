<?php
#Check if the user is logged in (Put this php code in all of your documents that require login)
session_start();

# Autologin to guest account
if (isset($_GET['user'])){

include("php/db.php");
    
$sql = $conn->prepare("SELECT * FROM users WHERE username = ?");
$sql->bind_param('s', $_GET['user']);
$sql->execute();
$sql->store_result();
$row = fetchAssocStatement($sql);
    
if ($row["guestpassword"] == "public"){
    $_SESSION['sig']="guest";
    $_SESSION['user']=$_GET['user'];
}
}

if(!isset($_SESSION['sig']))
{
	#go to the login page if sig doesn't exist in the SESSION array (i.e. the user is not logged in)
	echo('<script>window.location="index.php"</script>');		
} else if ($_SESSION['sig'] == "main"){
    echo('<script>window.location="gallery.php"</script>');
}

?>
<html>  
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" href="css/style.css" type="text/css">
<script type="text/javascript" src="js/jquery.min.js"></script>
<title>Photo Gallery</title>    
</head>
    
<body>
    
    <img src="img/pen.png" style="display:none">
    <img src="img/calendar.png" style="display:none">
    <img src="img/alt.png" style="display:none">
	
	<div class="dropdown">
	  <img src="img/setting.png" onclick="menu()" class="dropdown-button" id="dropdownimg">
	  <div id="myDropdown" class="dropdown-content">
		<a href="php/logout.php">Sign Out</a>
		<a href="privacypolicy.html">Privacy Policy</a>
		<a href="termsandconditions.html">Terms and Conditions</a>
	  </div>
	</div> 

    <div id="gallery"></div>
    
    <a href="guestmap.php"><img src="img/map.png" alt="Photo Map" class="photomap"></a>
    <cr>&copy; 2018 photomapa.com &nbsp</cr>        
    <a href="javascript:openModal('filterModal')"><img src="img/filter.png" alt="Filter Gallery" class="filterbutton"></a>
    
    <div id="filterModal" class="modal">
        <div class="modal-content-hidden">
            <a href="javascript:closeModal('filterModal')">
                <span class="close">&times;</span>
            </a>
            <h2>Filter Date <br> <input type="date" id="start"> <br> to <br> <input type="date" id="end"> <br> <button onclick="datefilter()" class="button">Filter</button></h2>
        </div>
    </div>
    
</body>
	
<script src="js/blazy.min.js"></script>
<script src="js/function.js"></script>
<script>plotguestimages();</script>
<script>
	var bLazy = new Blazy({
	success: function(element){
	setTimeout(function(){
	// We want to remove the loader gif now.
	// First we find the parent container
	// then we remove the "loading" class which holds the loader image
	var parent = element.parentNode;
	parent.className = parent.className.replace(/\bloading\b/,'');
	}, 200);
	}
	});
</script>
</html>

