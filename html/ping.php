<?php
require('ipaddr.php');

$cmd_hv = 'ping -c 1 -W 1 ' . HYPERVISOR_IP;
$cmd_sv = 'ping -c 1 -W 1 ' . SV_IP;

exec($cmd_hv, $hv_output ,$hv_retval);
exec($cmd_sv, $sv_output ,$sv_retval);

$json = array( "hv_ret" => $hv_retval, "hv_output" => $hv_output,
               "sv_ret" => $sv_retval, "sv_output" => $sv_output);
echo json_encode( $json ) ;
