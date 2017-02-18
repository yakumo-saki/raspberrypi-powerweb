<?php
require ('define.php');

// リセットボタンの長押しは意味が無い
$cmd = '../scripts/power.sh ' . RESET_PIN . ' short';
exec($cmd, $output ,$retval);

$json = array( "ret" => $retval, "output" => $output);
echo json_encode( $json ) ;
