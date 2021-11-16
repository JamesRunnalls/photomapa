$(document).ready(function(){
	$('#uploadform').ajaxForm({
		beforeSubmit:function(){
			$('#uploadStatus').html('Uploading... 0%');
		},
		uploadProgress:function(event, position, total, percentComplete){
           $('#uploadStatus').html('Uploading... '+percentComplete+"% <br>("+(position/1000000).toFixed(2)+"MB of "+(total/1000000).toFixed(2)+"MB)");
        },
		success:function(data){
			$('#uploadStatus').html(data);
			if (data == "Success"){
				location.reload();
			}
		},
		error:function(){
			$('#uploadStatus').html('Images upload failed, please try again.');
		}
	});
});

document.onkeydown = checkKey;
mymap = [];
lat = [];
lon = [];
window.onclick = function(event) {
    try {
        if (document.getElementById("myModal").style.display == "block") {
            var modal = document.getElementById('myModal');
        } else if (document.getElementById("uploadModal").style.display == "block") {
            var modal = document.getElementById('uploadModal');
        } else if (document.getElementById("filterModal").style.display == "block") {
            var modal = document.getElementById('filterModal');
        } else if (document.getElementById("editModal").style.display == "block") {
            var modal = document.getElementById('editModal');
        } else if (document.getElementById("passwordModal").style.display == "block") {
            var modal = document.getElementById('passwordModal');
        } else if (document.getElementById("gpasswordModal").style.display == "block") {
            var modal = document.getElementById('gpasswordModal');
        } else if (document.getElementById("infoModal").style.display == "block") {
            var modal = document.getElementById('infoModal');
        } else if (document.getElementById("myDropdown").style.display == "block") {
			if (event.target != document.getElementById('dropdownimg')) {
				document.getElementById("myDropdown").style.display = "none";
			}
        } else {}

        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    catch (e){
        console.log(e);
    }
	
}

function menu() {
	if (document.getElementById("myDropdown").style.display == "block"){
		document.getElementById("myDropdown").style.display = "none";
	} else {
		document.getElementById("myDropdown").style.display = "block";
	}
    
}

function plotimages(start, end) {
    // Set start and end filters is undefined
    if (start === undefined){
        var start = new Date(1000,1,1,1,1,1,1);  
    }
    if (end === undefined){
        var end = new Date(3000,1,1,1,1,1,1);
    }
    
    // Retrieve data from server
    arr =  ajaxconnect('php/connect.php');
    arr = dateparse(arr);
    
    // Sort the array such that images are shown in date order.
    arr = arr.sort(sortFunction);
    
    // Create the html for the gallery
    var gallery = '<div class="flex-container">'
    var lightbox = '<div id="myModal" class="modal"><a href = "javascript:closeModal()"><span class="close cursor">&times;</span></a><div class="modal-content">';
    var editbox = '<div id="editModal" class="modal"><a href = "javascript:closeModal(\'editModal\');openModal()"><span class="close cursor">&times;</span></a><div class="editboxcenter">';
    var dict = [];
    for (var i = 0; i < arr.length; i++){          
        if (arr[i]["datetime"] <= end && arr[i]["datetime"] >= start) {
            var x = i + 1
            
            if (arr[i]["latitude"] == "N/A"){
                var link = 'php/geotag.php#'+arr[i]["file"]
            } else {
                var link = 'map.php#'+arr[i]["latitude"]+'&'+arr[i]["longitude"]
            }
            dict[arr[i]["file"]] = x;
            
            
            if (arr[i]["simpledate"] == "NaN/NaN/NaN"){
                var sdate = '';
            } else {
                var sdate = '<img src="img/calendar.png" class="calendar">&nbsp'+arr[i]["simpledate"];
            }
            if (arr[i]["altitude"] == "N/A" || arr[i]["altitude"] == "" || arr[i]["altitude"] == "0" || arr[i]["altitude"] == 0){
                var salt = '';
            } else {
                var salt = '&nbsp &nbsp &nbsp &nbsp<a href="'+link+'" style="color: white;" >'+'<img src="img/alt.png" class="alt"></a>&nbsp'+arr[i]["altitude"]+'m';
            }
                 
            gallery = gallery + '<div><a href="javascript:openModal();currentSlide('+x+',\'mySlides\')"><img data-src="photos_c/'+arr[i]["file"]+'" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="img b-lazy loading"></a></div>';
            lightbox = lightbox + '<div class="mySlides"><a href = "javascript:closeModal();openModal(\'editModal\');currentSlide('+x+',\'editSlides\')"><img src="img/pen.png" class="edit"></a><a href="'+link+'" style="color: white;" ><img class="modaltext b-lazy loading" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="photos_c/'+arr[i]["file"]+'"></a><h3>'+arr[i]["description"]+'<br>'+sdate+salt+'</h3></div>';
            editbox = editbox + '<div class="editSlides"><center><form action="php/edit.php" method="post" enctype="multipart/form-data" target="_top"><h2>Edit Photo Information<br><table style="color:white;margin:auto;"><tr><td>Description</td><td><input type="text" name="description" value="'+arr[i]["description"]+'" style="width:160px"></td></tr><tr><td>Date</td><td><input type="date" name="date" value="'+reversdate(arr[i]["simpledate"])+'" style="width:160px"></td></tr><tr><td>Altitude</td><td><input type="number" name="altitude" step="0.0000000000001" value="'+arr[i]["altitude"]+'" style="width:160px"></td></tr><tr><td>Rotate</td><td><select name="rotate" style="width:160px"><option value="0">0 Degrees</option><option value="270">90 Degrees CW</option><option value="90">90 Degrees ACW</option><option value="180">180 Degrees</option></select></td></tr><tr><td>Latitude</td><td><input type="number" step="0.0000000000001" name="latitude" value="'+arr[i]["latitude"]+'" style="width:160px"></td></tr><tr><td>Longitude</td><td><input type="number" name="longitude" step="0.0000000000001" value="'+arr[i]["longitude"]+'" style="width:160px"></td></tr></table><a href="php/geotag.php#'+arr[i]["file"]+'&'+arr[i]["latitude"]+'&'+arr[i]["longitude"]+'" style="color:white">Geotag Using Map</a><br><input type="submit" value="Update" name="submit" class="button"><input type="submit" onclick="javascript:proceed()" value="Delete" name="submit" class="button"><input type="hidden" value="'+arr[i]["file"]+'" name="file" /></h2></form></center></div>';
        } 
    }     
    gallery = gallery + '</div>';
    lightbox = lightbox + '<a class="prev" onclick="plusSlides(-1,\'mySlides\')">&#10094;</a><a class="next" onclick="plusSlides(1,\'mySlides\')">&#10095;</a></div></div>';
    editbox = editbox + '</div></div>'
    var div = document.getElementById('gallery');
    div.innerHTML = gallery + lightbox + editbox;
    
    // Open the gallery if the image name is passed to the url
    var image = window.location.hash.substr(1);
    if (isNaN(dict[image])){console.log("NaN")} else {console.log("life");openModal();currentSlide(dict[image],"mySlides")}         
}

var upload = document.getElementById("fileToUpload");
var descID = document.getElementById("desc_id");
upload.addEventListener("change", function() {
	var len = upload.files.length;
	if (len > 1){
		descID.innerHTML = '<input type="text" value="" name="text" style="width:100%;" disabled>';
	} else if (upload.files[0].name.includes("gpx")){
		descID.innerHTML = '<select name="text" style="width:100%;"><option value=""></option><option value="Road Bike">Road Bike</option><option value="Hike">Hike</option><option value="Mountain Bike">Mountain Bike</option><option value="Climb">Climb</option><option value="Swim">Swim</option><option value="Via Ferrata">Via Ferrata</option><option value="Ski">Ski</option></select>';
	} else {
		descID.innerHTML = '<input type="text" name="text" style="width:100%;">';
	}
});

function proceed(){
    if (confirm('Are you sure you want to delete this image?')){
    }   else {
        event.preventDefault();
    }
}

function plotguestimages(start, end) {
    // Set start and end filters is undefined
    if (start === undefined){
        var start = new Date(1000,1,1,1,1,1,1);  
    }
    if (end === undefined){
        var end = new Date(3000,1,1,1,1,1,1);
    }
    
    // Retrieve data from server
    arr =  ajaxconnect('php/connect.php');
    arr = dateparse(arr);
    
    // Sort the array such that images are shown in date order.
    arr = arr.sort(sortFunction);
    
    // Create the html for the gallery
    var gallery = '<div class="flex-container">'
    var lightbox = '<div id="myModal" class="modal"><a href = "javascript:closeModal()"><span class="close cursor">&times;</span></a><div class="modal-content">';
    var dict = [];
    for (var i = 0; i < arr.length; i++){
        if (arr[i]["simpledate"] == "NaN/NaN/NaN"){
                var sdate = '';
            } else {
                var sdate = '<img src="img/calendar.png" class="calendar">&nbsp'+arr[i]["simpledate"];
            }
        if (arr[i]["altitude"] == "N/A" || arr[i]["altitude"] == "" || arr[i]["altitude"] == "0" || arr[i]["altitude"] == 0){
            var salt = '';
        } else {
            var salt = '&nbsp &nbsp &nbsp &nbsp<img src="img/alt.png" class="alt">&nbsp'+arr[i]["altitude"]+'m';
        }
        if (arr[i]["datetime"] <= end && arr[i]["datetime"] >= start) {
            var x = i + 1
            dict[arr[i]["file"]] = x;
            gallery = gallery + '<div><a href="javascript:openModal();currentSlide('+x+',\'mySlides\')"><img src="photos_c/'+arr[i]["file"]+'" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="img b-lazy loading"></a></div>';
            lightbox = lightbox + '<div class="mySlides"><a href="guestmap.php#'+arr[i]["latitude"]+'&'+arr[i]["longitude"]+'" style="color: white;" ><img class="modaltext b-lazy loading" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="photos_c/'+arr[i]["file"]+'"></a><h3>'+arr[i]["description"]+'<br>'+sdate+salt+'</h3></div>';
        } 
    }     
    gallery = gallery + '</div>';
    lightbox = lightbox + '<a class="prev" onclick="plusSlides(-1,\'mySlides\')">&#10094;</a><a class="next" onclick="plusSlides(1,\'mySlides\')">&#10095;</a></div></div>';
    var div = document.getElementById('gallery');
    div.innerHTML = gallery + lightbox;
    
    // Open the gallery if the image name is passed to the url
    var image = window.location.hash.substr(1);
    if (isNaN(dict[image])){console.log("NaN")} else {console.log("life");openModal();currentSlide(dict[image],"mySlides")}         
}

function plotmap(){
    var type = window.location.hash.substr(1);
    type = type.split('&');
    lat = 0;
    lon = -5;
    var zo = 2;
    if ( type.length == 2 && isNumeric(Number(type[0])) ) {
    lat = Number(type[0]);
    lon = Number(type[1]);
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
	
	var swisstopo = L.tileLayer('https://wmts20.geo.admin.ch/1.0.0/ch.swisstopo.pixelkarte-farbe/default/current/3857/{z}/{x}/{y}.jpeg');
    
    mymap = L.map('map', {
			    layers: [openstreet] // only add one!
		    }).setView([lat, lon], zo);
    
    var baseLayers = {
			"Terrain": openstreet,
			"Satellite": googleSat,
		};
	
	var overlayMaps = {
			"Swiss Topo": swisstopo
		};
    
    var layerControl = L.control.layers(baseLayers, overlayMaps, {position:'topleft'}).addTo(mymap);
	
    plotmarker(mymap,lat,lon);
    plottracks(mymap);
	
	$(document).on('click', function(ev){
		var latlng = mymap.mouseEventToLatLng(ev.originalEvent); 
		var latlngstr = "#"+latlng.lat+"&"+latlng.lng;
		var els=document.getElementsByName("location_track");
		console.log(els);
		for (var i=0;i<els.length;i++) {
			els[i].value = latlngstr;
		}
	});

}

function plotguestmap(){
    var type = window.location.hash.substr(1);
    type = type.split('&');
    lat = 0;
    lon = -5;
    var zo = 2;
    if ( type.length == 2 && isNumeric(Number(type[0])) ) {
    lat = Number(type[0]);
    lon = Number(type[1]);
    zo = 11;
    } 

    mymap = L.map('map').setView([lat, lon], zo);

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	maxZoom: 19,
	attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(mymap);

    plotguestmarker(mymap,lat,lon);
    plotguesttracks(mymap);

}

var log =[];
var register = [];

function plotmarker(mymap,lat,lon,start,end){
    // Set start and end filters is undefined
    if (start === undefined){
        var start = new Date(1000,1,1,1,1,1,1);  
    }
    if (end === undefined){
        var end = new Date(3000,1,1,1,1,1,1);
    }

    // Retrieve data from server
    arr =  ajaxconnect('php/connect.php');
    arr = dateparse(arr);
    
    // Create markers
    mapMarkers = [];
    for (var i = 0; i < arr.length; i++) {
        if (arr[i]["datetime"] == "Invalid Date") {
                arr[i]["datetime"] = new Date(1001,1,1,1,1,1,1);
            }
        if (arr[i]["latitude"] != "N/A" && arr[i]["datetime"] <= end && arr[i]["datetime"] >= start){
            if (arr[i]["simpledate"] == "NaN/NaN/NaN"){
                var sdate = '';
            } else {
                var sdate = '<img src="img/calendarb.png" class="calendar">&nbsp'+arr[i]["simpledate"];
            }
            if (arr[i]["altitude"] == "N/A" || arr[i]["altitude"] == "" || arr[i]["altitude"] == "0" || arr[i]["altitude"] == 0){
                var salt = '';
            } else {
                var salt = '&nbsp &nbsp &nbsp &nbsp<img src="img/altb.png" class="alt">&nbsp'+arr[i]["altitude"]+'m';
            }
            var marker = L.marker([arr[i]["latitude"], arr[i]["longitude"],]).addTo(mymap)
            .bindPopup('<a href="gallery.php#'+arr[i]["file"]+'"><img src="photos_c/'+arr[i]["file"]+'" style="width:300px;"></a><br><h4>'+arr[i]["description"]+'<br>'+sdate+salt+'</h4>');
            
            this.mapMarkers.push(marker);
        if (lat == arr[i]["latitude"] && lon == arr[i]["longitude"]) {
            marker.openPopup();
        } 
    }
}
}

function plotguestmarker(mymap,lat,lon,start,end){
    // Set start and end filters is undefined
    if (start === undefined){
        var start = new Date(1000,1,1,1,1,1,1);  
    }
    if (end === undefined){
        var end = new Date(3000,1,1,1,1,1,1);
    }

    // Retrieve data from server
    arr =  ajaxconnect('php/connect.php');
    arr = dateparse(arr);
    
    // Create markers
    mapMarkers = [];
    for (var i = 0; i < arr.length; i++) {
        if (arr[i]["datetime"] == "Invalid Date") {
                arr[i]["datetime"] = new Date(1001,1,1,1,1,1,1);
            }
        if (arr[i]["latitude"] != "N/A" && arr[i]["datetime"] <= end && arr[i]["datetime"] >= start){
            if (arr[i]["simpledate"] == "NaN/NaN/NaN"){
                var sdate = '';
            } else {
                var sdate = '<img src="img/calendarb.png" class="calendar">&nbsp'+arr[i]["simpledate"];
            }
            if (arr[i]["altitude"] == "N/A" || arr[i]["altitude"] == "" || arr[i]["altitude"] == "0" || arr[i]["altitude"] == 0){
                var salt = '';
            } else {
                var salt = '&nbsp &nbsp &nbsp &nbsp<img src="img/altb.png" class="alt">&nbsp'+arr[i]["altitude"]+'m';
            }
            var marker = L.marker([arr[i]["latitude"], arr[i]["longitude"],]).addTo(mymap)
            .bindPopup('<a href="guestgallery.php#'+arr[i]["file"]+'"><img src="photos_c/'+arr[i]["file"]+'" style="width:300px;"></a><br><h4>'+arr[i]["description"]+'<br>'+sdate+salt+'</h4>');
            this.mapMarkers.push(marker);
        if (lat == arr[i]["latitude"] && lon == arr[i]["longitude"]) {
            marker.openPopup();
        }
    }
}
}

function plottracks(mymap){
    // Retrieve data from server
    arr =  ajaxconnect('php/connecttrack.php');

    for (var i = 0; i < arr.length; i++) {
		var customLayer = "";
        var file = arr[i]["file"];
		var desc = arr[i]["description"];
		var col = "#1d00ff";
		
		var a,b,c,d,e,f,g;
		a = b = c = d = e = f = g = h = "";
		
		if (desc == "Road Bike"){
			col = "#0171de";
			a = "selected";
		} else if (desc == "Hike"){
			col = "#009000";
			b = "selected";
		} else if (desc == "Mountain Bike"){
			col = "#c16619";
			c = "selected";
		}else if (desc == "Climb"){
			col = "#f00";
			d = "selected";
		}else if (desc == "Swim"){
			col = "#03ecff";
			e = "selected";
		}else if (desc == "Via Ferrata"){
			col = "#ffba00";
			f = "selected";
		} else if (desc == "Ski"){
			col = "#b404c3";
			g = "selected";
		} else if (desc == "Snow Shoe"){
			col = "#FFB6C1";
			h = "selected";
		}

		customLayer = L.geoJson(null, {		
			style: {"color":col}   
		});
		
		var ht = '<form action="php/editgpx.php" method="post" enctype="multipart/form-data" target="_top"><select name="description" value="'+arr[i]["description"]+'" style="width:280px;text-align:center;color:black;border:none;height:25px;"><option value=""></option><option value="Road Bike" '+a+'>Road Bike</option><option value="Hike" '+b+'>Hike</option><option value="Mountain Bike" '+c+'>Mountain Bike</option><option value="Climb" '+d+'>Climb</option><option value="Swim" '+e+'>Swim</option><option value="Snow Shoe" '+h+'>Snow Shoe</option><option value="Via Ferrata" '+f+'>Via Ferrata</option><option value="Ski" '+g+'>Ski</option></select><br><input type="submit" value="Update" name="submit" style="margin-left:80px"><input type="submit" onclick="javascript:proceed()" value="Delete" name="submit"><input type="hidden" value="'+arr[i]["file"]+'" name="file" /><input type="hidden" value="#null&null" name="location_track" /></form>'
			
        var filetype = file.substr(file.length - 3);
        if (filetype == "gpx"){
            omnivore.gpx('track/'+file, null, customLayer).bindPopup(ht).addTo(mymap);  
        } else if (filetype == "kml"){
            omnivore.kml('track/'+file, null, customLayer).bindPopup(ht).addTo(mymap);
        }
    }
}

function plotguesttracks(mymap){
	 // Retrieve data from server
    arr =  ajaxconnect('php/connecttrack.php');

    for (var i = 0; i < arr.length; i++) {
		var customLayer = "";
        var file = arr[i]["file"];
		var desc = arr[i]["description"];
		var col = "#1d00ff";
		
		var a,b,c,d,e,f,g;
		a = b = c = d = e = f = g = h = "";
		
		if (desc == "Road Bike"){
			col = "rgb(35, 131, 226)";
			a = "selected";
		} else if (desc == "Hike"){
			col = "#80b204";
			b = "selected";
		} else if (desc == "Mountain Bike"){
			col = "#d6a327";
			c = "selected";
		}else if (desc == "Climb"){
			col = "#f00";
			d = "selected";
		}else if (desc == "Swim"){
			col = "#ce00ff";
			e = "selected";
		}else if (desc == "Via Ferrata"){
			col = "#f0bd48";
			f = "selected";
		} else if (desc == "Ski"){
			col = "#ffffff";
			g = "selected";
		} else if (desc == "Snow Shoe"){
			col = "#FFB6C1";
			h = "selected";
		}

		customLayer = L.geoJson(null, {		
			style: {"color":col}   
		});
		
		var ht = '<h4><br>'+arr[i]["description"]+'</h4>'
			
        var filetype = file.substr(file.length - 3);
        if (filetype == "gpx"){
            omnivore.gpx('track/'+file, null, customLayer).bindPopup(ht).addTo(mymap);  
        } else if (filetype == "kml"){
            omnivore.kml('track/'+file, null, customLayer).bindPopup(ht).addTo(mymap);
        }
    }
}

function plottracksgeo(mymap){
	 // Retrieve data from server
    arr =  ajaxconnect('connecttrack.php');

    for (var i = 0; i < arr.length; i++) {
		var customLayer = "";
        var file = arr[i]["file"];
		var desc = arr[i]["description"];
		var col = "#1d00ff";
		
		var a,b,c,d,e,f,g;
		a = b = c = d = e = f = g = h = "";
		
		if (desc == "Road Bike"){
			col = "rgb(35, 131, 226)";
			a = "selected";
		} else if (desc == "Hike"){
			col = "#80b204";
			b = "selected";
		} else if (desc == "Mountain Bike"){
			col = "#d6a327";
			c = "selected";
		}else if (desc == "Climb"){
			col = "#f00";
			d = "selected";
		}else if (desc == "Swim"){
			col = "#ce00ff";
			e = "selected";
		}else if (desc == "Via Ferrata"){
			col = "#f0bd48";
			f = "selected";
		} else if (desc == "Ski"){
			col = "#ffffff";
			g = "selected";
		} else if (desc == "Snow Shoe"){
			col = "#FFB6C1";
			h = "selected";
		}

		customLayer = L.geoJson(null, {		
			style: {"color":col}   
		});
		
		var ht = '<h4><br>'+arr[i]["description"]+'</h4>'
			
        var filetype = file.substr(file.length - 3);
        if (filetype == "gpx"){
            omnivore.gpx('../track/'+file, null, customLayer).bindPopup(ht).addTo(mymap);  
        } else if (filetype == "kml"){
            omnivore.kml('../track/'+file, null, customLayer).bindPopup(ht).addTo(mymap);
        }
    }
}

function ajaxconnect(connect){
    var arr;        
    $.ajax({ 
        type: "Post",
        url: connect,                     
        dataType: 'json',
        async: false,      
        success: function(data)          //on recieve of reply
        {
            arr = data;
        } 
    });
    return arr; 
}

function dateparse(arr){
    for (var i = 0; i < arr.length; i++) {
    if (arr[i]["datetime"].length > 10){
        var k = arr[i]["datetime"].split(/:| /);
        var photodate = new Date(k[0],k[1]-1,k[2],k[3],k[4],k[5],0);
    } else {
        var k = arr[i]["datetime"].split("-");
        var photodate = new Date(k[0],k[1]-1,k[2],0,0,0,0);
    }

    var simpledate = photodate.getDate() + '/' + (photodate.getMonth()+1) + '/' + photodate.getFullYear()
    arr[i]["datetime"] = photodate;
    arr[i].simpledate = simpledate;
    if (arr[i]["datetime"] == "Invalid Date") {
            arr[i]["datetime"] = new Date(1001,1,1,1,1,1,1);
            }
        arr[i].timestamp = arr[i]["datetime"].getTime();   
    }
    return arr;
}

function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function mapdatefilter() {
    var start = new Date(document.getElementById("start").value);
    var end = new Date(document.getElementById("end").value);
    for(var i = 0; i < this.mapMarkers.length; i++){
        this.mymap.removeLayer(this.mapMarkers[i]);
    }
    
    plotmarker(mymap,lat,lon,start,end);
    document.getElementById("filterModal").style.display = "none";
}

function mapclearfilter() {
    for(var i = 0; i < this.mapMarkers.length; i++){
    this.mymap.removeLayer(this.mapMarkers[i]);
    }
     plotmarker(mymap,lat,lon);
     document.getElementById("filterModal").style.display = "none";
}

function gallerydatefilter() {
    var start = new Date(document.getElementById("start").value);
    var end = new Date(document.getElementById("end").value);
    plotimages(start,end);
    document.getElementById('filterModal').style.display = "none";
}

function galleryclearfilter() {
    plotimages();
    document.getElementById('filterModal').style.display = "none";
}

function sortFunction(a, b) {
    if (a["timestamp"] === b["timestamp"]) {
        return 0;
    } else {
        return (a["timestamp"] > b["timestamp"]) ? -1 : 1;
    }
}

function openModal(xmodal) {
    if (xmodal == null){
         document.getElementById("myModal").style.display = "block";
		var bLazy = new Blazy();
    } else {
    document.getElementById(xmodal).style.display = "block";
    }
	if (document.getElementById("myDropdown").style.display == "block"){
		document.getElementById("myDropdown").style.display = "none";
	}
}

function closeModal(xmodal) {
     if (xmodal == null){
         document.getElementById("myModal").style.display = "none";
    } else {
    document.getElementById(xmodal).style.display = "none";
    }
}

function plusSlides(n,modal) {
	var bLazy = new Blazy();
    showSlides(slideIndex += n,modal);
}

function currentSlide(n,modal) {
    showSlides(slideIndex = n,modal);
}

function showSlides(n,modal) {
    var i;
    var slides = document.getElementsByClassName(modal);
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slides[slideIndex-1].style.display = "block"; 
}

function checkKey(e) {
    e = e || window.event;
    if (e.keyCode == '37') {
        plusSlides(-1,"mySlides")
    }
    else if (e.keyCode == '39') {
       plusSlides(1,"mySlides")
    }
}

function reversdate(date){
    sp = date.split("/");
    if (sp[1].length == 1){
        sp[1] = "0"+sp[1];
    }
    if (sp[0].length == 1){
        sp[0] = "0"+sp[0];
    }
    return sp[2]+"-"+sp[1]+"-"+sp[0];
}

function show(input) {
    var x = document.getElementById(input);
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}

function zoomregister() {
    mymap.setView([65, 110], 3);
    register.openPopup();
}

function zoomlog() {
    mymap.setView([50, -5], 3);
    log.openPopup();
}

function zoomforgot() {
    mymap.setView([50, -110], 3);
    forgot.openPopup();
}
