<?php
#Check if the user is logged in (Put this php code in all of your documents that require login)
session_start();
if(!isset($_SESSION['sig']))
{
	#go to the login page if sig doesn't exist in the SESSION array (i.e. the user is not logged in)
	echo('<script>window.location="index.php"</script>');		
} else if ($_SESSION['sig'] == "main"){
    echo('<script>window.location="map.php"</script>');
}


?>
<html>
<head>
	
	<title>Photo Map</title>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin=""/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
    <script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js'></script>
</head>
<body>
    
<img src="img/calendarb.png" style="display:none">
<img src="img/altb.png" style="display:none">

<div id="map" style="width: 100%; height: 100%; z-index: 0;"></div>
    
<a href="guestgallery.php"><img src="img/pict.png" alt="Photo Gallery" class="photogallery"></a>
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
    

<script src="js/function.js"></script>
<script>plotguestmap();</script>
</body>
</html>