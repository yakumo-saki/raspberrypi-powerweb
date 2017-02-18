<?php
require ('define.php');

$type = $_GET["type"];
if ($type == "short" || $type == "long") {
	$cmd = '../scripts/power.sh ' . $POWER_PIN . ' ' . $type;
	exec($cmd, $output ,$retval);
} else {
	$retval = -127;
	$output = "PARAMETER ERROR";
}

$json = array( "ret" => $retval, "output" => $output);
echo json_encode( $json ) ;
