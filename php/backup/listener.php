<?php
session_start();
header('HTTP/1.1 200 OK');

$resp = 'cmd=_notify-validate';
foreach ($_POST as $parm => $var) 
	{
	$var = urlencode(stripslashes($var));
	$resp .= "&$parm=$var";
	}

  $photos           = $_POST['option_selection1']

  if ($photos == "200 Extra"){
      $extraphotos = 200;
  } else if ($photos == "500 Extra"){
      $extraphotos = 500;
  } else if ($photos == "1000 Extra"){
      $extraphotos = 1000;
  }
  
$httphead = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$httphead .= "Content-Type: application/x-www-form-urlencoded\r\n";
$httphead .= "Content-Length: " . strlen($resp) . "\r\n\r\n";

$errno ='';
$errstr='';
 
$fh = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

 if (!$fh) {
 
// Uh oh. This means that we have not been able to get thru to the PayPal server.  It's an HTTP failure
//
// You need to handle this here according to your preferred business logic.  An email, a log message, a trip to the pub..
           } 
		   
// Connection opened, so spit back the response and get PayPal's view whether it was an authentic notification		   
		   
else 	{
           fputs ($fh, $httphead . $resp);
		   while (!feof($fh))
				{
				$readresp = fgets ($fh, 1024);
				if (strcmp ($readresp, "VERIFIED") == 0) 
					{
                     include("db.php");

                    // import username

                    $user = $_SESSION["user"];

                    // perform query
                    $sql = $conn->prepare("SELECT * FROM users WHERE username = ?");
                    $sql->bind_param('s', $user);
                    $sql->execute();
                    $sql->store_result();
                    $row = fetchAssocStatement($sql);
                    $newphotos = $row["payment"] + $extraphotos;
                    
                    $sql = $conn->prepare("UPDATE users SET payment = ? WHERE username = ?");
                    $sql->bind_param('ss', $newphotos, $user);
                    $sql->execute();
                    $conn->close();
                    echo('<script>window.location="../gallery.php"</script>'); 


					}
 
				else if (strcmp ($readresp, "INVALID") == 0) 
					{
 
//  			Man alive!  A hacking attempt?
 
					}
				}
fclose ($fh);
		}
//
//
// STEP 6 - Pour yourself a cold one.
//
//

?>