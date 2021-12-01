<?php

include('phpFITFileAnalysis.php');

function fit2kml($file){

    $options = ['fix_data' => ['all']];
    
    $pFFA = new fitconvert\phpFITFileAnalysis('../track/'.$file,$options);
    $timestamp = min($pFFA->data_mesgs['record']['timestamp']);
    $position_lat = $pFFA->data_mesgs['record']['position_lat'];
    $position_long = $pFFA->data_mesgs['record']['position_long'];
    $altitude = $pFFA->data_mesgs['record']['altitude'];
    $distance = max($pFFA->data_mesgs['record']['distance']);
    $keys = array_keys($position_lat);
    $data = '<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2">
    <Document id="feat_3">
        <open>1</open>
        <Placemark id="feat_4">
            <name>Date: '.date("d-m-Y",$timestamp).' Distance: '.$distance.'km</name>
            <LineString id="geom_1">
                <coordinates>';
    for ($i = 0; $i < count($position_lat); $i++) {
    $data = $data.$position_long[$keys[$i]].','.$position_lat[$keys[$i]].','.$altitude[$keys[$i]].' '."\n";
    }

    $data = $data.'</coordinates>
            </LineString>
        </Placemark>
    </Document>
</kml>';
       
       
    $file2 = substr($file, 0, -3).'kml';
    $fp = fopen('../track/'.$file2, 'w');
    fwrite($fp, print_r($data, TRUE));
    fclose($fp);
    unset($pFFA);
	unlink('../track/'.$file); 
	return $file2;
   }

?>