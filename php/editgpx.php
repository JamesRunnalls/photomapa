<?php

if(isset($_POST["submit"])) {
	session_start();
	$file = $_POST["file"];
	$submit = $_POST["submit"];
	$description = $_POST["description"];
	$location = $_POST["location_track"];
		
	include("db.php");

	// import username

	$user = $_SESSION["user"];

	// perform query
	if ($submit == "Delete"){
		$sql = $conn->prepare("DELETE FROM track WHERE username = ? AND file = ?");
		$sql->bind_param('ss', $user, $file);
		$sql->execute();
		unlink('../track/'.$file);
		$conn->close();
		header('Cache-Control: no-cache');
		header('Pragma: no-cache');
		header( "Location: ../map.php" );
	} else if ($submit == "Update"){
		$sql = $conn->prepare("UPDATE track SET description =  ? WHERE username = ? AND file = ?");
		$sql->bind_param('sss', $description, $user, $file);
		$sql->execute();
		$conn->close();
		header('Cache-Control: no-cache');
		header('Pragma: no-cache');
		header( "Location: ../map.php".$location );
	}

} else {header( "Location: ../gallery.php" );}

?>