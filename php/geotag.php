<?php
#Check if the user is logged in (Put this php code in all of your documents that require login)
session_start();
if(!isset($_SESSION['sig']))
{
	#go to the login page if sig doesn't exist in the SESSION array (i.e. the user is not logged in)
	echo('<script>window.location="index.php"</script>');		
} else if ($_SESSION['sig'] == "guest"){
    echo('<script>window.location="../guestgallery.php"</script>');
}

?>
<html>
<head>
	
    <meta name="description" content="Photomapa is a dynamic photo gallery and map that lets you store your global memories.">
    <meta name="author" content="James Runnalls">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin=""/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
    <script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js'></script>
    <title>Photomapa - Geotag</title>
</head>
<body>

<div id="map" style="width: 100%; height: 100%; z-index: 0;"></div>
        
<a href="javascript:savelocation()"><img src="../img/save.png" alt="Save Location" class="savebutton"></a>
    
<div id="photo"></div>

<script src="../js/function.js"></script>
<script>
    
var type = window.location.hash.substr(1);
type = type.split('&');
lat = 0;
lon = -5;
var zo = 2;
if ( type.length == 3 && isNumeric(Number(type[1])) ) {
lat = Number(type[1]);
lon = Number(type[2]);
zo = 11;
} 

var openstreet = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	maxZoom: 19,
	attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	});
    
var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
maxZoom: 20,
subdomains:['mt0','mt1','mt2','mt3']
});

mymap = L.map('map', {
            layers: [openstreet] // only add one!
        }).setView([lat, lon], zo);

var baseLayers = {
        "Terrain": openstreet,
        "Satellite": googleSat
    };

var layerControl = L.control.layers(baseLayers, null,{position:'topleft'}).addTo(mymap);
	
plottracksgeo(mymap)
    
 var theMarker = {};

mymap.on('click',function(e){
    lat = e.latlng.lat;
    lon = e.latlng.lng;
        //Clear existing marker, 

        if (theMarker != undefined) {
              mymap.removeLayer(theMarker);
        };

    //Add a marker to show where you clicked.
     theMarker = L.marker([lat,lon]).addTo(mymap);  
});
    
var file = type[0];
    
document.getElementById("photo").innerHTML = '<img src="../photos_c/'+file+'" class="photo">';

function savelocation(){   
 $.ajax({ 
        type: 'post',
        url: 'geotagconnect.php',
        data: {
            lat: lat,
            lon: lon,
            file: file
        },
        success: function( data ) {
        }
    });  
    
window.location.href = "../map.php#"+lat+"&"+lon;
    
 }
    
if ( type.length == 3 && isNumeric(Number(type[1])) ) {
    theMarker = L.marker([lat,lon]).addTo(mymap); 
} 

    
</script>
    
    
</body>
</html>