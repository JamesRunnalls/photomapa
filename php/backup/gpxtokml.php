<?php
$files = array_slice(scandir('../gpx'), 2);
foreach($files as $item){
    
    gpx2kml($item);
    
    unlink('../gpx/'.$item);
}


function gpx2kml($file){
$xml=simplexml_load_file("../gpx/".$file) or die("Error: Cannot create object");
    
$timestamp = substr($xml->trk->trkseg->trkpt[0]->time,0,10);
$data = '<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2">
    <Document id="feat_3">
        <open>1</open>
        <Placemark id="feat_4">
            <name>Date: '.$timestamp.'</name>
            <LineString id="geom_1">
                <coordinates>';

foreach ($xml->trk->trkseg->trkpt as $trkpt){
    $data = $data.$trkpt['lon'].','.$trkpt['lat'].','.$trkpt->ele.' '."\n";
    }

$data = $data.'</coordinates>
            </LineString>
        </Placemark>
    </Document>
</kml>';

    $file2 = substr($file, 0, -3).'kml';
    $fp = fopen('../kml/'.$file2, 'w');
    fwrite($fp, print_r($data, TRUE));
    fclose($fp);
}

?>