<?php

$type = $_GET["power"];
if ($type == "on") {
	$cmd = '/opt/scripts/aircon/aircon-big-on.sh ' . $type;
	exec($cmd, $output ,$retval);
} elseif ($type == "off") {
	$cmd = '/opt/scripts/aircon/aircon-big-off.sh ' . $type;
	exec($cmd, $output ,$retval);
} else {
	$retval = -127;
	$output = "PARAMETER ERROR";
}

$json = array( "ret" => $retval, "output" => $output);
echo json_encode( $json ) ;
