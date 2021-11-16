<?php
#Check if the user is logged in (Put this php code in all of your documents that require login)
session_start();
if(!isset($_SESSION['sig']))
{
	#go to the login page if sig doesn't exist in the SESSION array (i.e. the user is not logged in)
	echo('<script>window.location="index.php"</script>');		
} else if ($_SESSION['sig'] == "guest"){
    echo('<script>window.location="guestmap.php"</script>');
}

?>
<html>
<head>
	
    <meta name="description" content="Photomapa is a dynamic photo gallery and map that lets you store your global memories.">
    <meta name="author" content="James Runnalls">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin=""/>
    <script src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.form.js"></script>
    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
    <script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js'></script>
    <title>Photomapa - Map</title>
</head>
<body>
    
<img src="img/calendarb.png" style="display:none">
<img src="img/altb.png" style="display:none">
<cr>&copy; 2018 photomapa.com &nbsp</cr>
<div id="map" style="width: 100%; height: 100%; z-index: 0;"></div>
    
<a href="gallery.php"><img src="img/pict.png" alt="Photo Gallery" class="photogallery"></a>
          
<a href="javascript:openModal('filterModal')"><img src="img/filter.png" alt="Filter Gallery" class="filterbutton"></a>

<div id="filterModal" class="modal">
        <div class="modal-content-hidden">
            <a href="javascript:closeModal('filterModal')">
                <span class="close">&times;</span>
            </a>
            <h2>Filter Date <br> <input type="date" id="start"> <br> to <br> <input type="date" id="end"></h2> <center> <button onclick="gallerydatefilter()" class="button">Filter</button><br><br>
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

<script src="js/function.js"></script>
<script>plotmap();</script>
</body>
</html>