<?php
#Check if the user is logged in (Put this php code in all of your documents that require login)
session_start();
if(!isset($_SESSION['sig']))
{
	#go to the login page if sig doesn't exist in the SESSION array (i.e. the user is not logged in)
	echo('<script>window.location="index.php"</script>');		
} else if ($_SESSION['sig'] == "guest"){
    echo('<script>window.location="guestgallery.php"</script>');
}

?>
<html>  
<head>
<meta name="description" content="Photomapa is a dynamic photo gallery and map that lets you store your global memories.">
<meta name="author" content="James Runnalls">
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" href="css/style.css" type="text/css">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<title>Photomapa - Gallery</title>    
</head>
    
<body>
<img src="img/pen.png" style="display:none">
<img src="img/calendar.png" style="display:none">
<img src="img/alt.png" style="display:none">
<img src="img/tl.png" class="tl">
<img src="img/tr.png" class="tr">
<img src="img/bl.png" class="bl">
<img src="img/br.png" class="br">
	
	
<div class="dropdown">
  <img src="img/setting.png" onclick="menu()" class="dropdown-button" id="dropdownimg">
  <div id="myDropdown" class="dropdown-content">
    <a href="php/logout.php">Sign Out</a>
    <a href="javascript:openModal('passwordModal')">Change Password</a>
    <a href="javascript:openModal('gpasswordModal')">Change Guest Password</a>
    <a href="javascript:openModal('infoModal')">Information</a>
    <a href="privacypolicy.html">Privacy Policy</a>
	<a href="termsandconditions.html">Terms and Conditions</a>
  </div>
</div> 
	
	
	
    <div id="gallery"></div>
    
    <a href="map.php"><img src="img/map.png" alt="Photo Map" class="photomap"></a> 
	<cr>&copy; 2018 photomapa.com &nbsp</cr>
	<div id="passwordModal" class="modal">
        <div class="modal-content-hidden">
            <a href="javascript:closeModal('passwordModal')">
                <span class="close">&times;</span>
            </a>
            <h2>Change Password</h2>
           
            <form action="php/editpassword.php" method="post" enctype="multipart/form-data" target="_top" style="width:inherit;"> 
                <p align="left">
                Old Password: <br><input type="password" name="oldpassword" style="width:100%;"><br><br>
                New Password: <br><input type="password" name="password" style="width:100%;"><br><br>
                Confirm New Password: <br><input type="password" name="confirmpassword" style="width:100%;"><br>
                </p><center>
                <input type="submit" value="Update" name="submit" class="button"></center>
			</form>
        </div>
    </div>
	
	<div id="gpasswordModal" class="modal">
        <div class="modal-content-hidden">
            <a href="javascript:closeModal('gpasswordModal')">
                <span class="close">&times;</span>
            </a>
            <h2>Change Guest Password</h2>
			
			Leave blank to have open access to gallery.<br>
            
                <form action="php/editpassword.php" method="post" enctype="multipart/form-data" target="_top">
                <p align="left">
                New Password: <br><input type="password" name="password" style="width:100%;"><br><br>
                Confirm New Password: <br><input type="password" name="confirmpassword" style="width:100%;"><br><br>
                </p><center>
                <input type="submit" value="Update Guest" name="submit" class="button"></center>
                </form>
        </div>
    </div>
	
	<div id="infoModal" class="modal">
        <div class="modal-content-hidden">
            <a href="javascript:closeModal('infoModal')">
                <span class="close">&times;</span>
            </a>
            <h2>Information</h2>
            Public access to your gallery is available at:<br>
            
            <a href="https://photomapa.com/guestgallery.php?user=<?php echo $_SESSION['user']; ?>" style="color: blue">https://photomapa.com/guestgallery.php?user=<?php echo $_SESSION['user']; ?></a><br>
                
            Set a guest password to require guests to login. 
            
				Guests do not have edit privaleges.
        </div>
    </div>
            
    <a href="javascript:openModal('filterModal')"><img src="img/filter.png" alt="Filter Gallery" class="filterbutton"></a>
    
    <div id="filterModal" class="modal">
        <div class="modal-content-hidden">
            <a href="javascript:closeModal('filterModal')">
                <span class="close">&times;</span>
            </a>
            <h2>Filter Date <br> <input type="date" id="start"> <br> to <br> <input type="date" id="end"></h2> <center> <button onclick="gallerydatefilter()" class="button">Filter</button>
            <button onclick="galleryclearfilter()" class="button">Clear Filter</button></center>
        </div>
    </div>
    
    <a href="javascript:openModal('uploadModal')"><img src="img/upload.png" alt="Upload File" class="uploadbutton"></a>
    
    <div id="uploadModal" class="modal">
        <div class="modal-content-hidden">
            <a href="javascript:closeModal('uploadModal')">
                <span class="close">&times;</span>
            </a>
            <form action="php/upload.php" id="uploadform" method="post" enctype="multipart/form-data" target="_top">
				<h2>Upload Files</h2> <br>
				<div style="font-size:13px">Drop your file below. </div>
                <input type="file" name="fileToUpload[]" id="fileToUpload" multiple="multiple" class="upload"><br><br>
                Description: <br><div id="desc_id"><input type="text" name="text" style="width:100%;"></div><br><br>
                Accepted: <br>jpg, png, gpx, kml, fit. <br><br>
                <input type="submit" value="Upload" name="submit" class="button">
				<br><br><div id="uploadStatus"></div>
            </form>
        </div>
    </div>
    
</body>
<script src="js/blazy.min.js"></script>
<script src="js/function.js"></script>
<script>plotimages();</script>
<script>
	var bLazy = new Blazy();
</script>
</html>

