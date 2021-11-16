<?php
error_reporting(E_ERROR | E_PARSE);

function gps2Num($coordPart){
    $parts = explode('/', $coordPart);
    if(count($parts) <= 0)
    return 0;
    if(count($parts) == 1)
    return $parts[0];
    return floatval($parts[0]) / floatval($parts[1]);
}

function get_image_info($image = ''){
    $exif = exif_read_data($image, "IFOD");
    $file           = $exif['FileName'];
    $datetime       = $exif['DateTimeOriginal'];
    $GPSLatitudeRef = $exif['GPSLatitudeRef'];
    $GPSLatitude    = $exif['GPSLatitude'];
    $GPSLongitudeRef= $exif['GPSLongitudeRef'];
    $GPSLongitude   = $exif['GPSLongitude'];
    $altitude       = gps2Num($exif['GPSAltitude']);
    $orientation    = $exif['Orientation'];
    
    if(is_float($altitude)) {} else {$altitude = 'N/A';}
    if(is_string($datetime)) {} else {$datetime = 'N/A';}
    
    if(is_array($GPSLatitude)) {
        $lat_degrees = count($GPSLatitude) > 0 ? gps2Num($GPSLatitude[0]) : 0;
        $lat_minutes = count($GPSLatitude) > 1 ? gps2Num($GPSLatitude[1]) : 0;
        $lat_seconds = count($GPSLatitude) > 2 ? gps2Num($GPSLatitude[2]) : 0;
        $lat_direction = ($GPSLatitudeRef == 'W' or $GPSLatitudeRef == 'S') ? -1 : 1;
        $latitude = $lat_direction * ($lat_degrees + ($lat_minutes / 60) + ($lat_seconds / (60*60)));
    } else {$latitude = "N/A";}

    if(is_array($GPSLatitude)) {
        $lon_degrees = count($GPSLongitude) > 0 ? gps2Num($GPSLongitude[0]) : 0;
        $lon_minutes = count($GPSLongitude) > 1 ? gps2Num($GPSLongitude[1]) : 0;
        $lon_seconds = count($GPSLongitude) > 2 ? gps2Num($GPSLongitude[2]) : 0;
        $lon_direction = ($GPSLongitudeRef == 'W' or $GPSLongitudeRef == 'S') ? -1 : 1;
        $longitude = $lon_direction * ($lon_degrees + ($lon_minutes / 60) + ($lon_seconds / (60*60)));
    } else {$longitude = "N/A";}
        
    

    return array('file'=>$file,'datetime'=>$datetime,'latitude'=>$latitude, 'longitude'=>$longitude,'altitude'=>$altitude,'orientation'=>$orientation);
    }

function info_to_xml($imginfo,$xml){
    $section = " ";
    try {
       $text = "../txt/" . substr($imginfo['file'], 0, -4) . ".txt";
       $section = file_get_contents($text) . $section;
    } finally {}
    
    $photo = $xml->addChild('photo');
    $photo->addChild('file', $imginfo['file']);
    $photo->addChild('datetime', $imginfo['datetime']);
    $photo->addChild('latitude', $imginfo['latitude']);
    $photo->addChild('longitude', $imginfo['longitude']);
    $photo->addChild('altitude', $imginfo['altitude']);
    $photo->addChild('orientation', $imginfo['orientation']);
    $photo->addChild('description', $section );
    return $xml;
    }

function copyimage($file){
    copy('../photos/'.$file,'../photos_c/'.$file);
}

function photorotate ($file){
    $img = imagecreatefromjpeg('../photos_c/'.$file);
    if (!$img){$img = imagecreatefromstring(file_get_contents('../photos_c/'.$file));}
    $imginfo = get_image_info('../photos/'.$file);
    $or = $imginfo['orientation'];
    if ($or == 8){$deg = 90;} 
    elseif ($or == 6){$deg = 270;}
    elseif ($or == 3){$deg = 180;}
    else {$deg = 0;}
    $rotate = imagerotate($img, $deg, 0);
    imagejpeg($rotate, '../photos_c/'.$file);
    imagedestroy($img);
        
}

function shrinkimage($file){
    $hmin = 400;
    $wmin = 600;
    list($width, $height) = getimagesize('../photos_c/'.$file);
    $hrat = $height/$hmin;
    $wrat = $width/$wmin;
    if ( $hrat < $wrat) {
         $newheight = $hmin;
         $newwidth = $width/$hrat;
         } else {
         $newwidth = $wmin; 
         $newheight = $height/$wrat;
         }
   $thumb = imagecreatetruecolor($newwidth, $newheight);
   $img = imagecreatefromjpeg('../photos_c/'.$file);
   if (!$img){$img = imagecreatefromstring(file_get_contents('../photos_c/'.$file));}
   imagecopyresampled($thumb, $img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
   //adjust the 100 for greater compression if required
   imagejpeg($thumb, '../photos_c/'.$file, 100);    
}

//create json of kml files

$kml = json_encode(array_slice(scandir('../kml'), 2));
$fp = fopen('../kml.json', 'w');
fwrite($fp, $kml);
fclose($fp);

// create xml file

$xml = new SimpleXMLElement('<xml/>');
$files = array_slice(scandir('../photos'), 2);

foreach($files as $item){
    
    //get image name
    $imageURL = '../photos/'.$item;

    //get image info
    $imginfo = get_image_info($imageURL);

    //add image to xml file
    $xml = info_to_xml($imginfo,$xml);
}

$xml->asXML('../photos.xml');

foreach($files as $item){
    if (file_exists('../photos_c/'.$item)) {} else {copyimage($item);
                                                 photorotate($item);
                                                 shrinkimage($item);
                                                }    
}
 
?>